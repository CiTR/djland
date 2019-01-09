<?php

namespace App\Forms;

use Kris\LaravelFormBuilder\Form;

class ShowForm extends Form
{
    public function buildForm()
    {
        // Days of the week
        $days = array();
        for ($i=0; $i<7; $i++) {
            $days[] = date('l', strtotime("Sunday +{$i} days"));
        }

        $this
            ->add('title', 'text', [
                'rules' => 'required',
            ])
            ->add('host', 'text', [
                'label' => 'Hosts Display Name'
            ])
            ->add('weekday', 'select', [
                'choices' => $days,
                'rules' => 'required|digits_between:0,6'
            ])
            ->add('start_time', 'time', [
                'rules' => 'required'
            ])
            ->add('end_time', 'time', [
                'rules' => 'required'
            ])
            ->add('is_active', 'checkbox', [
                'label' => 'Active Show',
                'rules' => 'boolean'
            ])
            ->add('is_explicit', 'checkbox', [
                'label' => 'Explicit Content',
                'rules' => 'boolean',
            ])
            ->add('website', 'text')
            ->add('rss', 'text')
            ->add('podcast_xml', 'text')
            ->add('users', 'select', [
                'choices' => \App\User::all()->pluck('name', 'id')->toArray(),
                'empty_value' => 'Select Host Users',
                'attr' => [
                    'multiple' => 'multiple',
                    'class' => 'form-control select2',
                ],
            ])
            ->add('submit', 'submit', [
                'label' => 'Submit',
            ]);
    }
}
