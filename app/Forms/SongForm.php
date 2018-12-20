<?php

namespace App\Forms;

use Kris\LaravelFormBuilder\Form;

class SongForm extends Form
{
    public function buildForm()
    {
        $this
            ->add('title', 'text', [
                'rules' => 'required',
            ])
            ->add('artist', 'text')
            ->add('length', 'text', [
                'rules' => 'regex:/^[0-9]*[:]?[0-9]*$/',
            ]);

        if ($this->getData('includeHiddenId')) {
            $this->add('id', 'hidden');
        }
    }
}
