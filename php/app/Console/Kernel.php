<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        \App\Console\Commands\BunqTransactionOverviewCommand::class,
        \App\Console\Commands\BunqMakeTransactionCommand::class,
        \App\Console\Commands\BunqCheckRefundsCommand::class,
        \App\Console\Commands\CleanerCommand::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('cleaner')->hourly();
        $schedule->command('bunq:check-refunds')->hourly();
        $schedule->command('bunq:make-transaction')->everyMinute();
        $schedule->command('bunq:transaction-overview')->dailyAt('23:55');
    }

    /**
     * Register the Closure based commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        require base_path('routes/console.php');
    }
}
