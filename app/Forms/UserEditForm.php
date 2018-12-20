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
                'select', ['choices' =>
                ["AB" => "Alberta", "BC" => "British Columbia", "MB" => "Manitoba",
                "NB" => "New Brunswick", "NL" => "Newfoundland and Labrador", "NT" => "Northwest Territories",
                "NS" => "Nova Scotia", "NU" => "Nunavut", "ON" => "Ontario",
                "PE" => "Prince Edward Island", "QC" => "Quebec", "SK" => "Saskatchewan",
                "YT" => "Yukon",],])
            ->add('postal_code', 'text')
            ->add('is_canadian_citizen', 'select', ['choices' => [true => "Yes", false => "No",],])
            ->add('email', 'email')
            ->add('primary_phone', 'tel')
            ->add('secondary_phone', 'tel')
            ->add('submit', 'submit');
    }
}
