<?php

namespace Tests\Browser;

use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use App\User;


class LoginTest extends DuskTestCase
{
    public function setUp() {
        parent::setUp();
        $this->user = factory(User::class)->create(['email' => 'example@example.com']);
        $this->pass = 'secret';
    }

    public function tearDown() {
        $this->user->delete();
    }

    public function testLoginValidCredentials() {
        $this->browse(function ($browser) {
            $browser->visit('/login')
                    ->type('email', $this->user->email)
                    ->type('password', $this->pass)
                    ->press('Login')
                    ->assertPathIs('/home');
        });
    }

    public function testLoginInvalidCredentialsWrongPassword() {
        $this->browse(function ($browser) {
            $browser->visit('/login')
                ->type('email', $this->user->email)
                ->type('password', 's'.$this->pass)
                ->press('Login')
                ->assertPathIs('/login')
                ->assertSee('These credentials do not match our records.');
        });
    }

    public function testLoginInvalidCredentialsWrongEmail() {
        $this->browse(function ($browser) {
            $browser->visit('/login')
                ->type('email', 'e'.$this->user->email)
                ->type('password', $this->pass)
                ->press('Login')
                ->assertPathIs('/login')
                ->assertSee('These credentials do not match our records.');
        });
    }

    public function testLoginInvalidCredentialsWrongEmailPassword() {
        $this->browse(function ($browser) {
            $browser->visit('/login')
                ->type('email', 'e'.$this->user->email)
                ->type('password', 's'.$this->pass)
                ->press('Login')
                ->assertPathIs('/login')
                ->assertSee('These credentials do not match our records.');
        });
    }



}
