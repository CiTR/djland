<?php

namespace Tests\Browser;

use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;

use App\User;

class RegisterTest extends DuskTestCase
{

//    public function setUp()
//    {
//        parent::setUp();
//        $this->user = factory(User::class)->create(['email' => 'example@example.com']);
//    }

//    public function tearDown()
//    {
//        $this->user->delete();
//    }

    public function testRegisterNewUserValid() {
        $this->browse(function ($browser) {
            $user = factory(User::class)->create();
            $user->delete();

            $curr = $browser->visit('/register');
            $array = json_decode( $user, true );
            unset($array['email_verified_at']);
            unset($array['is_approved']);
            unset($array['taken_station_tour']);
            unset($array['taken_tech_training']);
            unset($array['taken_prog_training']);
            unset($array['taken_prod_training']);
            unset($array['taken_spoken_training']);
            unset($array['updated_at']);
            unset($array['created_at']);
            unset($array['id']);
            unset($array['comments']);

            //TODO: doesn't work on checkbox and drop-downs
            unset($array['is_canadian_citizen']);
            unset($array['province']);
            unset($array['is_new']);
            unset($array['is_alumni']);
            unset($array['is_approved']);
            unset($array['is_discorder_contributor']);
            unset($array['school_year']);
            unset($array['course_integrate']);

            foreach($array as $key=>$val) {
                print($key. "\xA");
                $curr->type($key, $val);
            }
            $curr->type('password', 'secret')
                 ->type('password_confirmation', 'secret');
            $curr->press('Submit');
            $curr->assertPathIs('/home');
        });
    }
}
