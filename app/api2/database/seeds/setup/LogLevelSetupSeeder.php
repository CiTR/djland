<?php

use Illuminate\Database\Seeder;
use App\LogLevel;

class LogLevelSetupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //Bigger number is bigger problem
        $levels = array('DEBUG', 'INFO', 'NOTICE', 'WARNING', 'ERROR',
        'CRITICAL', 'ALERT', 'EMERGENCY');
        foreach ($levels as $level) {
            LogLevel::create(array('level' => $level));
        }
    }
}
