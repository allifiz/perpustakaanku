<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MemberMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        // Pastikan user login dan role bukan admin
        if (!Auth::check() || Auth::user()->role === 'admin') {
            return redirect('/');
        }

        return $next($request);
    }
}