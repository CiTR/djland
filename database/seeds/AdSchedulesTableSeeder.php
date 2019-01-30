<?php

use Illuminate\Database\Seeder;

use App\AdSchedule;

class AdSchedulesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        AdSchedule::firstOrCreate([
            'name'              => 'Station ID on the Hour',
            'type'              => 'station_id',
            'description'       => 'You are listening to CiTR Radio 101.9FM, broadcasting from unceded Musqueam territory in Vancouver',
            'minutes_past_hour' => 0,
        ]);

        AdSchedule::firstOrCreate([
            'name'              => 'Any Ad 20 minutes past',
            'type'              => 'ad',
            'description'       => 'Any Ad',
            'minutes_past_hour' => 20,
        ]);

        AdSchedule::firstOrCreate([
            'name'              => 'Any Ad 40 minutes past',
            'type'              => 'ad',
            'description'       => 'Any Ad',
            'minutes_past_hour' => 40,
        ]);

        AdSchedule::firstOrCreate([
            'name'              => 'Any PSA 20 minutes past',
            'type'              => 'psa',
            'description'       => 'Any PSA',
            'minutes_past_hour' => 20,
        ]);

        AdSchedule::firstOrCreate([
            'name'              => 'Any Promo 40 minutes past',
            'type'              => 'promo',
            'description'       => 'Any Promo',
            'minutes_past_hour' => 40,
        ]);

        AdSchedule::firstOrCreate([
            'name'              => 'Announce next show',
            'type'              => 'announcement',
            'description'       => 'Please announce the upcoming program',
            'minutes_into_show' => -5,
            'time_start'        => '08:00:00',
            'time_end'          => '23:59:59',
        ]);
    }
}
