<?php

use Illuminate\Database\Seeder;
use App\Option;
use Carbon\Carbon;

class DJLandOptionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Option::create(array(
            'djland_option' => 'membership_cutoff_year',
            'value' => "{Carbon::now()->year - 1}/{Carbon::now()->year}"
        ));
        Option::create(array(
            'djland_option' => 'membership_resouces',
            'value' => "<p><strong>Member resources:</strong></p>"
        ));
    }
}
