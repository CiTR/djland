<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;


class ClearRejectedSubmissions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'clearRejectedSubmissions';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clears the library submissions table of rejected entries that are over a month old';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $submissions = Submissions::where('is_trashed','=',1)->where('updated_at','<',Carbon::now())->get();
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
    }
}
