<?php

use Illuminate\Database\Seeder;

class ShowsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\Show::class, 50)->create()->each(function ($show) {
            $users = App\User::all()->random(rand(1,3));

            $show->users()->sync($users->pluck('id'));
        });
    }
}
