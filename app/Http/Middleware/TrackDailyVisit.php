<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Str;

class TrackDailyVisit
{
    public function handle($request, Closure $next)
    {
        // (Opsional) Skip bot/crawler supaya data lebih bersih
        $ua = (string) ($request->userAgent() ?? '');
        if ($ua && preg_match('/bot|crawl|slurp|spider|curl|wget|httpclient|python-requests/i', $ua)) {
            return $next($request);
        }

        // Ambil/generate visitor id (cookie first-party)
        $vuid = $request->cookie('vuid');
        if (!$vuid) {
            $vuid = (string) Str::uuid();
            // Cookie 180 hari, httpOnly, sameSite=Lax
            Cookie::queue(cookie(
                'vuid',
                $vuid,
                60 * 24 * 180,                  // minutes
                null,
                null,
                $request->isSecure(),           // secure jika https
                true,                           // httpOnly
                false,                          // raw
                'lax'                           // sameSite
            ));
        }

        $hash   = sha1($vuid);
        $today  = now()->toDateString();
        $now    = now();

        // Update baris jika sudah ada; kalau belum ada -> insert
        $updated = DB::table('visits')
            ->where('visitor_hash', $hash)
            ->whereDate('visited_at', $today)
            ->update([
                'last_seen_at' => $now,
                'hits'         => DB::raw('hits + 1'),
                'updated_at'   => $now,
            ]);

        if ($updated === 0) {
            DB::table('visits')->insert([
                'visitor_id'   => $vuid,
                'visitor_hash' => $hash,
                'ip_address'   => (string) $request->ip(),
                'user_agent'   => Str::limit($ua, 255, ''),
                'visited_at'   => $today,
                'first_seen_at'=> $now,
                'last_seen_at' => $now,
                'hits'         => 1,
                'created_at'   => $now,
                'updated_at'   => $now,
            ]);
        }

        return $next($request);
    }
}
