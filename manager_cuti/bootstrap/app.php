<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Console\Scheduling\Schedule;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'role' => \App\Http\Middleware\CheckRole::class,
        ]);
    })
    ->withSchedule(function (Schedule $schedule): void {
        // Reset annual leave quota every January 1st at 00:00
        // Cron format: minute hour day month day-of-week
        // 0 0 1 1 * = Every year on January 1st at 00:00
        $schedule->command('leave:reset-quota')
            ->cron('0 0 1 1 *')
            ->timezone('Asia/Makassar')
            ->description('Reset annual leave quota for all eligible employees');
        
        // Sync employee status based on leave requests daily at 00:00
        // This ensures employees who finished their leave are automatically set back to active
        $schedule->command('leave:sync-employee-status')
            ->daily()
            ->timezone('Asia/Makassar')
            ->description('Sync employee active status based on leave requests');
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
