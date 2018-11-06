<?php

use Illuminate\Database\Seeder;

class MembershipTypesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $default_types = array(
            'Staff',
            'Community',
            'Student',
            'Lifetime',
        );

        foreach ($default_types as $type) {
            App\MembershipType::firstOrCreate([
                'name' => $type,
            ]);
        }
    }
}
