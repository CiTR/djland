<?php

use Illuminate\Database\Seeder;

use App\Genre;
use App\User;

class GenresSetupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $genres = array();
        //genresList and crtcList should be the same length and same ordered
        $genresList = array(
            "Electronic",
            "Experimental",
            "Hip Hop / R&B / Soul",
            "International",
            "Jazz / Classical",
            "Punk / Hardcore / Metal",
            "Rock / Pop / Indie",
            "Roots / Blues / Folk",
            'Talk'
        );
        $crtcList = array(
            '20',
            '30',
            '20',
            '30',
            '30',
            '20',
            '20',
            '30',
            '10'
        );
        $i = 0;
        foreach ($genresList as $genre) {
            $genres[$i]['genre'] = $genre;
            $i++;
        }
        $i = 0;
        foreach ($crtcList as $crtc) {
            $genres[$i]['crtc'] = $crtc;
            $i++;
        }

        $admin_id = User::where('username', '=', 'Admin')->get()['member_id'];
        foreach ($genres as $key => $genre) {
            Genre::create(array(
                'genre' => $genre['genre'],
                'created_by' => $admin_id,
                'updated_by' => $admin_id,
                'default_crtc_category' => $genre['crtc']
            ));
        }
    }
}
