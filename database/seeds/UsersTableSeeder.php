<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\User::class, 50)->create()->each(function ($user) {
            // Do stuff
        });

        $first = App\User::first();
        $first->email = 'technicalmanager@citr.ca'; // Hey, that's my email!
        $first->membership_type_id = 1;
        $first->save();
    }
}
