<?php

namespace Tests\Browser;

use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;

use App\User;

class RegisterTest extends DuskTestCase
{

    public function setUp()
    {
        //TODO: BUG - course_integrate should be renamed to is_course_integrate
        parent::setUp();
        $this->uneditableFields = ['email_verified_at', 'is_approved', 'taken_station_tour',
            'taken_tech_training', 'taken_prog_training', 'taken_prod_training',
            'taken_spoken_training', 'updated_at', 'created_at', 'id', 'comments', 'course_integrate'];
        //TODO: Better method for Drop Down?
        $this->dropDownFields = ['province', 'school_year'];
    }

    public function tearDown()
    {
        session()->flush();
        parent::tearDown();
    }

    public function testRegisterNewUserValid() {
        $user = factory(User::class)->create();
        $user->delete();
        $array = json_decode( $user, true );

        //TODO: Implement email verified at
        $this->browse(function ($browser) use ($user, &$array) {
            $curr = $browser->visit('/register');

            foreach($this->uneditableFields as $field) {
                unset($array[$field]);
            }

            foreach($array as $key=>$val) {
                if (in_array($key, $this->dropDownFields)) {
                    $curr->select($key, $val);
                }
                else if (starts_with($key, 'is_')){
                    if ($val) {
                        $curr->check($key);
                    }
                }
                else {
                $curr->type($key, $val);
                }
            }

            $curr->type('password', 'secret')
                 ->type('password_confirmation', 'secret');
            $curr->press('Submit');
            $curr->assertPathIs('/home');
        });

        foreach($array as $key=>$val) {
            $this->assertDatabaseHas('users',[$key=>$val]);
        }
    }

}
