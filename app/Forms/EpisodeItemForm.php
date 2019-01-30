<?php

namespace App\Forms;

use Kris\LaravelFormBuilder\Form;

class EpisodeItemForm extends Form
{
    public function buildForm()
    {
        $this
            ->add('order', 'hidden')
            ->add('artist', 'text')
            ->add('title', 'text')
            ->add('album', 'text')
            ->add('start_datetime', 'time', [
                'label' => 'Start Time',
            ])
            ->add('duration', 'text')
            // Replace this with ISO 639-1
            ->add('language', 'select', [
                'choices' => config('djland.languages', []),
                'label' => 'Primary Language',
                'default_value' => 'en',
            ]);

        if ($this->getData('includeHiddenId')) {
            $this->add('id', 'hidden');
        }
    }
}
