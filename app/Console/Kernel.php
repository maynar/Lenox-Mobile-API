<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use App\Console\Commands\Alert;
use App\Console\Commands\RipeProveedor;
use App\Console\Commands\ChangeStatus;
use App\Console\Commands\SincCTIT;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        Alert::class,
        RipeProveedor::class,
        ChangeStatus::class,
        SincCTIT::class
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('remito:SendMail')
                 ->hourly();
        $schedule->command('ripe:ctit')
                 ->hourly();
        $schedule->command('remitos:status')
                 ->everyFiveMinutes();
         $schedule->command('sinc:ctit')
                 ->hourly();
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
