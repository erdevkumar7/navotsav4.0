<?php

use App\Providers\AuthServiceProvider;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Validation\ValidationException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'auth.admin' => \App\Http\Middleware\RedirectIfNotAdmin::class,
            'auth.vendor' => \App\Http\Middleware\RedirectIfNotVendor::class,
            'isSuspend' => \App\Http\Middleware\CheckAccountSuspended::class,
        ]);
    })->withProviders([
        AuthServiceProvider::class
    ])->withSchedule(function (Schedule $schedule) {
        $schedule->command('raffle:finalize-events')->everyTwoHours();
    })
    ->withExceptions(function (Exceptions $exceptions) {
        // $exceptions->render(function (ValidationException $e, $request) {
        //     return response()->json([
        //         'status' => false,
        //         'errors' => $e->errors(),
        //         'message' => 'Validation failed'
        //     ], 200);
        // });
        //
    })->create();
