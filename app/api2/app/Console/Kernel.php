<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Submissions as Submissions;
use Carbon\Carbon;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        \App\Console\Commands\Inspire::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        //Example task
        //$schedule->command('inspire')
        //         ->hourly();
        //Task to delete submissions in the trash over a month old
        //Using Cabon magic from http://carbon.nesbot.com/docs/#Difference
        $schedule->call(function () {
                     Submissions::where('deleted','=',1)->where(Carbon::createFromDate('date_submitted')->diffInDays(Carbon::today()),'>',31)->delete();
                 })->dailyAt('04:00');
    }
}
