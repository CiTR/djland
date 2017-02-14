<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class BasicTest extends TestCase
{
    /**
     * Visits the API root - if this fails there is a large problem
     *
     * @return void
     */
    public function testBasicFunctionality()
    {
        $this->visit('/APIinfo')
             ->see('Welcome to DJLand API V2.0');
    }
}
