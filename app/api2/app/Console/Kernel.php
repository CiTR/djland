<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Submissions as Submissions;

class Kernel extends ConsoleKernel
{
  /**
   * The Artisan commands provided by your application.
   *
   * @var array
   */
  protected $commands = [
    \App\Console\Commands\Inspire::class,
    \App\Console\Commands\ClearRejectedSubmissions::class,
    \App\Console\Commands\FixMembershipYear2022::class,
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
    $schedule->command('clearRejectedSubmissions')
      ->dailyAt('04:00');
  }
}
