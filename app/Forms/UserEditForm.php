<?php

namespace App\Forms;

use Kris\LaravelFormBuilder\Form;


class UserEditForm extends Form
{
    public function buildForm()
    {
        $this
            ->add('first_name', 'text')
            ->add('last_name', 'text')
            ->add('preferred_name', 'text')
            ->add('address', 'textarea')
            ->add('city', 'text')
            ->add('province',
                'select', ['choices' => config('provinces')])
            ->add('postal_code', 'text')
            ->add('is_canadian_citizen', 'checkbox')
            ->add('email', 'email')
            ->add('primary_phone', 'tel')
            ->add('secondary_phone', 'tel')
            ->add('about', 'textarea')
            ->add('skills', 'textarea')
            ->add('exposure', 'textarea')
            ->add('submit', 'submit');
    }

}
