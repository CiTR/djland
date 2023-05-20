<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class TestsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        //Call the standard setup seed
        $this->call(SetupSeeder::class);
        //Then add in more data to assist testing
        //TODO
        Model::reguard();
    }
}
