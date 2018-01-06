<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class SetupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        $this->call(AdminUserSetupSeeder::class);
        $this->call(LogLevelSetupSeeder::class);
        $this->call(DJLandOptionsSetupSeeder::class);
        //Genres must be seeded before subgenres
        $this->call(GenresSetupSeeder::class);
        $this->call(SubgenresSetupSeeder::class);

        Model::reguard();
    }
}
