<?php

use Illuminate\Database\Seeder;
use \Spatie\Tags\Tag;

class TrainingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Tag::findOrCreate(["Station Tour",
            "Technical",
            "Production",
            "Programming",
            "Spoken Word",]);
    }
}

