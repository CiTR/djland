<?php

namespace App\Forms;

use Kris\LaravelFormBuilder\Form;

class UserCreateForm extends Form
{
    public function buildForm()
    {
        $this
            ->add('email', 'email')
            ->add('password', 'password')
            ->add('password_confirmation', 'password')
            ->add('first_name', 'text')
            ->add('last_name', 'text')
            ->add('preferred_name', 'text')
            ->add('address', 'textarea')
            ->add('city', 'text')
            ->add('province',
                'select', ['choices' => \Config::get('provinces')])
            ->add('postal_code', 'text')
            ->add('is_canadian_citizen', 'checkbox')
            ->add('is_new', 'checkbox')
            ->add('is_alumni', 'checkbox')
            ->add('is_discorder_contributor','checkbox')
            // TODO: Drop down for year pairs, faculties
            ->add('member_since', 'text')
            ->add('faculty', 'text')
            ->add('school_year', 'select', ['choices' => ['1','2','3','4','5+']])
            ->add('student_no', 'text')
            ->add('course_integrate', 'checkbox')
            // TODO: Placeholders for these three
            ->add('about', 'textarea')
            ->add('skills', 'textarea')
            ->add('exposure', 'textarea')


            ->add('primary_phone', 'tel')
            ->add('secondary_phone', 'tel')
            ->add('submit', 'submit');
    }
}
