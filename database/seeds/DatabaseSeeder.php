<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(RolesTableSeeder::class);
        $this->call(UsersTableSeeder::class);
        $this->call(AlbumsTableSeeder::class);
        $this->call(ShowsTableSeeder::class);
        $this->call(AdSchedulesTableSeeder::class);
        $this->call(InterestsSeeder::class);
        $this->call(TrainingsSeeder::class);
    }
}
