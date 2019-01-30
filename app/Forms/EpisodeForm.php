<?php

namespace App\Forms;

use Kris\LaravelFormBuilder\Form;

class EpisodeForm extends Form
{
    public function buildForm()
    {
        $this
            ->add('show_id', 'select', [
                'choices' => \App\Show::all()->pluck('title', 'id')->toArray(),
                'label' => 'Show',
                'empty_value' => 'Select Show',
                'rules' => 'required|exists:shows,id',
                'attr' => [
                    'class' => 'form-control select2',
                ],
            ])
            ->add('host', 'text', [
                'rules' => 'required',
            ])
            ->add('start_date', 'date')
            ->add('start_time', 'time')
            ->add('end_date', 'date')
            ->add('end_time', 'time')
            ->add('spokenword_duration', 'number')
            // Replace this with ISO 639-1
            ->add('language', 'select', [
                'choices' => config('djland.languages', []),
                'label' => 'Primary Language',
                'default_value' => 'en',
            ])
            // Replace this with array of values (see: EpisodeFactory)
            ->add('broadcast_type', 'select', [
                'choices' => config('djland.broadcast_types', []),
                'default_value' => 'Live',
            ])
            ->add('title', 'text', [
                'label' => 'Episode Title',
            ])
            ->add('description', 'textarea', [
                'label' => 'Episode Description',
            ])
            ->add('submit', 'submit', [
                'label' => 'Submit',
            ]);
    }
}
