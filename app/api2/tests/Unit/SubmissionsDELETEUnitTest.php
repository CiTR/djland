<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class SubmissionsDELETEUnitTest extends TestCase
{
    //Skip middleware
    use WithoutMiddleware;
    //Don't actually change our DB
    use DatabaseTransactions;

    /**
     * Tests to see if all the DELETE routes are resolving as expeceted
     *
     * @return void
     */
    public function testDELETERoutes()
    {
        //No delete routes yet
    }
}
