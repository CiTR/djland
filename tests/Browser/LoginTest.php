<?php

namespace Tests\Browser;

use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use App\User;


class LoginTest extends DuskTestCase
{
    public function setUp()
    {
        parent::setUp();
        $this->user = factory(User::class)->create(['email' => 'example@example.com']);
    }

    public function tearDown()
    {
        $this->user->delete();
    }

    public function testLoginValidCredentials() {


        $this->browse(function ($browser) {
            $browser->visit('/login')
                    ->type('email', 'example@example.com')
                    ->type('password', 'secret')
                    ->press('Login')
                    ->assertPathIs('/home');

        });
    }

    public function testLoginInvalidCredentialsWrongPassword() {
        $this->browse(function ($browser) {
            $browser->visit('/login')
                ->type('email', 'example@example.com')
                ->type('password', 'secret1')
                ->press('Login')
                ->assertPathIs('/login')
                ->assertSee('These credentials do not match our records.');

        });
    }

    public function testLoginInvalidCredentialsWrongEmail() {
        $this->browse(function ($browser) {
            $browser->visit('/login')
                ->type('email', 'eexample@example.com')
                ->type('password', 'secret')
                ->press('Login')
                ->assertPathIs('/login')
                ->assertSee('These credentials do not match our records.');

        });
    }

    public function testLoginInvalidCredentialsWrongEmailPassword() {
        $this->browse(function ($browser) {
            $browser->visit('/login')
                ->type('email', 'eexample@example.com')
                ->type('password', 'secret1')
                ->press('Login')
                ->assertPathIs('/login')
                ->assertSee('These credentials do not match our records.');

        });
    }



}
