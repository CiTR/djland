<?php

use Illuminate\Database\Seeder;

class AlbumsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\Album::class, 50)->create()->each(function ($album) {
            $song_count = rand(5,15);
            for ($i=0; $i<$song_count; $i++) {
                $album->songs()->save(factory(App\Song::class)->make());
            }
        });
    }
}
