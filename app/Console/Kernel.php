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
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')
        //          ->hourly();

        // Here are going to be the functions to be executed every certain amount of time
        // $schedule->call('App\Http\Controllers\AboutController@FunctionUsedInSchedule')->everyMinute();

        $schedule->call(function () {
            $controller = new \App\Http\Controllers\AboutController();
            $controller->sendLoanReminders();
        })->everyMinute();

        // $schedule->call(function () {
        //     $controller = new \App\Http\Controllers\AboutController();
        //     $controller->FunctionUsedInSchedule();
        // })->daily();
        
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
