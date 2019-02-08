<?php

use Illuminate\Database\Seeder;
use \Spatie\Tags\Tag;

class InterestsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Tag::findOrCreate(["Tabling at events & concerts",
            "Promos and Outreach",
            "Audio Editing",
            "Music Department",
            "Podcasting",
            "Digital Library",
            "Live Remote Broadcasting",
            "Radio Show Hosting",
            "Programming Committee",
            "DJ Training",
            "Web and Tech",
            "Accessibility Collective",
            "Arts Report Collective",
            "Gender Empowerment Collective",
            "Indigenous Collective",
            "Music Affairs Collective",
            "News Collective",
            "Sports Collective",
            "UBC Affairs Collective",
            "Photography for CiTR 101.9 & Discorder",
            "Writing for Discorder",
            "Art for Discorder",
            "Other",], 'interest');
    }
}


