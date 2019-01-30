<?php

use Illuminate\Database\Seeder;

class ShowsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\Show::class, 50)->create()->each(function ($show) {
            $users = App\User::all()->random(rand(1,3));

            $show->users()->sync($users->pluck('id'));

            $episode_count = rand(5,50);
            for ($i=0; $i<$episode_count; $i++) {
                $episode = factory(App\Episode::class)->make();
                $show->episodes()->save($episode);

                // Episode Items
                $episode_item_count = rand(5,15);
                for ($j=0; $j<$episode_item_count; $j++) {
                    $state = (rand(0,1)) ? 'without_song' : 'random_song';
                    $episode_item = factory(App\EpisodeItem::class)->states($state)->make();
                    $episode->episodeItems()->save($episode_item);
                }
            }
        });
    }
}
