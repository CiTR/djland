<?php

namespace App\Forms;

use Kris\LaravelFormBuilder\Form;

class AdScheduleForm extends Form
{
    public function buildForm()
    {
        $this
            ->add('name', 'text', [
                'rules' => 'required', 
            ])
            ->add('type', 'select', [
                'choices' => config('djland.ad_types'),
                'rules' => 'required',
                'attr' => [
                    'class' => 'form-control select2',
                ],
            ])
            ->add('description', 'textarea')
            ->add('minutes_into_show', 'number', [
                'rules' => 'required_without:minutes_past_hour|nullable',
            ])
            ->add('minutes_past_hour', 'number', [
                'rules' => 'required_without:minutes_into_show|min:0|nullable',
            ])
            ->add('time_start', 'time', [
                'label' => 'Time of day ad runs (start)',
                'attr' => [
                    'step' => 1,
                ],
            ])
            ->add('time_end', 'time', [
                'label' => 'Time of day ad runs (end)',
                'attr' => [
                    'step' => 1,
                ],
            ])
            ->add('start_date', 'date', [
                'label' => 'Start running ad on:',
            ])
            ->add('start_time', 'time', [
                'label_show' => false,
                'attr' => [
                    'step' => 1,
                ],
            ])
            ->add('end_date', 'date', [
                'label' => 'End running ad on:',
            ])
            ->add('end_time', 'time', [
                'label_show' => false,
                'attr' => [
                    'step' => 1,
                ],
            ])
            ->add('submit', 'submit', [
                'label' => 'Submit',
            ]);
    }
}
