<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'role' => \Spatie\Permission\Middleware\RoleMiddleware::class,
            'permission' => \Spatie\Permission\Middleware\PermissionMiddleware::class,
            'role_or_permission' => \Spatie\Permission\Middleware\RoleOrPermissionMiddleware::class,
            'log.activity' => \App\Http\Middleware\LogUserActivity::class,
        ]);

        // Add the ConvertNullToEmptyString middleware globally
        $middleware->append(\App\Http\Middleware\ConvertNullToEmptyString::class);

        // Add the LogUserActivity middleware to the api group
        $middleware->prependToGroup('api', \App\Http\Middleware\LogUserActivity::class);
    })
    ->withSchedule(function ($schedule) {
        $schedule->command('app:custom-task')->dailyAt('10:00');
        $schedule->command('app:policy-expiration-task')->dailyAt('10:30');
        $schedule->command('whatsapp:send-notifications')->dailyAt('13:00');
        $schedule->command('commissions:generate')->everyMinute();
        $schedule->command('agent:update-settlements')->everyMinute();

    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
