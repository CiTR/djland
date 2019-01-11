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
        //TODO: Implement email verified at
        $this->browse(function ($browser) {
            $user = factory(User::class)->create();
            $user->delete();
            $curr = $browser->visit('/register');
            $array = json_decode( $user, true );

            $uneditableFields = ['email_verified_at', 'is_approved', 'taken_station_tour',
                                 'taken_tech_training', 'taken_prog_training', 'taken_prod_training',
                                 'taken_spoken_training', 'updated_at', 'created_at', 'id', 'comments'];

            //TODO: doesn't work on checkbox and drop-downs
            $untackledFields = ['is_canadian_citizen', 'province', 'is_new', 'is_alumni', 'is_approved',
                                'is_discorder_contributor', 'school_year', 'course_integrate'];

            foreach(array_merge($uneditableFields, $untackledFields) as $field) {
                unset($array[$field]);
            }

            foreach($array as $key=>$val) {
                //print($key. "\xA");
                $curr->type($key, $val);
            }
            $curr->type('password', 'secret')
                 ->type('password_confirmation', 'secret');
            $curr->press('Submit');
            $curr->assertPathIs('/home');
        });
    }
}
