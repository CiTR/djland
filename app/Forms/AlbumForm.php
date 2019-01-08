<?php

namespace App\Forms;

use Kris\LaravelFormBuilder\Form;

class AlbumForm extends Form
{
    public function buildForm()
    {
        $this
            ->add('artist', 'text', [
                'rules' => 'required',
            ])
            ->add('title', 'text', [
                'rules' => 'required',
            ])
            ->add('label', 'text')
            ->add('catalog', 'text')
            ->add('description', 'textarea')
            ->add('submit', 'submit', [
                'label' => 'Submit',
            ]);
    }
}
