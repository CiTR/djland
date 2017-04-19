<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Submissions as Submissions;
use Carbon\Carbon;
use Log;

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
                    //Move all entries to the Rejected table
                    $submissions = Submissions::where('is_trashed','=',1)->where('updated_at','<',Carbon::now()->get();
                    foreach($submission as $submissons){
                        $rejected = Rejected::create([
                            'artist' => $submission['artist'],
                            'title' => $submission['title'],
                            'contact' => $submission['contact'],
                            'label' => $submission['label'],
                            'cancon' => $submission('cancon'),
                            'femcon' => $submission('femcon'),
                            'local' => $submission('local'),
                            'description' => $submission['description'],
                            'catalog' => $submission['catalog'],
                            'format_id' => $submission('format_id'),
                            'submitted' => $submission('submitted'),
                            'review_comments' => $submission['review_comments'],
                        ]);
                        //Log that we deleted things
                        Log::notice("Moved rejected submisson from the submission table to the rejected table. Created:");
                        Log::notice($rejected);
                    }
                    //And then delete
                    Submissions::where('is_trashed','=',1)->where('updated_at','<',Carbon::now()->subMonth())->delete();
                 })->dailyAt('04:00');
    }
}
