<?php

namespace App\Forms;

use Kris\LaravelFormBuilder\Form;

class UserInterestsForm extends Form
{
    public function buildForm()
    {
        $this
            ->add("Tabling at events & concerts", 'checkbox')
            ->add("Promos and Outreach", 'checkbox')
            ->add("Audio Editing", 'checkbox')
            ->add("Music Department", 'checkbox')
            ->add("Podcasting", 'checkbox')
            ->add("Digital Library", 'checkbox')
            ->add("Live Remote Broadcasting", 'checkbox')
            ->add("Radio Show Hosting", 'checkbox')
            ->add("Programming Committee", 'checkbox')
            ->add("DJ Training", 'checkbox')
            ->add("Web and Tech", 'checkbox')
            ->add("Accessibility Collective", 'checkbox')
            ->add("Arts Report Collective", 'checkbox')
            ->add("Gender Empowerment Collective", 'checkbox')
            ->add("Indigenous Collective", 'checkbox')
            ->add("Music Affairs Collective", 'checkbox')
            ->add("News Collective", 'checkbox')
            ->add("Sports Collective", 'checkbox')
            ->add("UBC Affairs Collective", 'checkbox')
            ->add("Photography for CiTR 101.9 & Discorder", 'checkbox')
            ->add("Writing for Discorder", 'checkbox')
            ->add("Art for Discorder", 'checkbox')
            ->add("Other", 'checkbox');
    }
}
