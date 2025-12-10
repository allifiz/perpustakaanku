<?php

use Illuminate\Foundation\Application;
use App\Http\Middleware\TrackDailyVisit;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->redirectUsersTo(function () {
             $user = \Illuminate\Support\Facades\Auth::user();
             if ($user && $user->isAdmin()) {
                 return route('admin.dasbor');
             }
             return route('member.dashboard');
        });

        // Register middleware aliases
        $middleware->alias([
            'admin' => \App\Http\Middleware\AdminMiddleware::class,
            'member' => \App\Http\Middleware\MemberMiddleware::class,
            'verified' => \Illuminate\Auth\Middleware\EnsureEmailIsVerified::class,
            'track'    => \App\Http\Middleware\TrackDailyVisit::class,
            'prevent-back-history' => \App\Http\Middleware\PreventBackHistory::class,
        ]);
        // << tambahkan ini agar TrackDailyVisit aktif di semua route 'web'
    })
    ->withExceptions(function (Exceptions $exceptions) {
    })->create();