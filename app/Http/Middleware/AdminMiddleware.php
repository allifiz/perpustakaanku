<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        // Pastikan user login dan role adalah admin
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            return redirect('/');
        }

        return $next($request);
    }
}