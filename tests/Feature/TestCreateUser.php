<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

use App\User;

class TestCreateUser extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testExample()
    {
        $this->assertTrue(true);
    }

    public function testUserLoggedIn() {
        $user = factory(User::class)->create();
        $response = $this->actingAs($user)->get('register');
        $response->assertRedirect('home');
    }

}
