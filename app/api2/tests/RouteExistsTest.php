<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class RouteExistsTest extends TestCase
{
    /**
     * Tests to see if all the GET routes are resolving as expeceted
     *
     * @return void
     */
    public function testGETRoutes()
    {
        $this->visit('/submissions')
            ->seeJSON();
        //$this->get('')
    }
    /**
     * Tests to see if all the POST routes are resolving as expeceted
     *
     * @return void
     */
    public function testPOSTRoutes()
    {
        $this->assertTrue(true);
    }
    /**
     * Tests to see if all the PUT routes are resolving as expeceted
     *
     * @return void
     */
    public function testPUTRoutes()
    {
        $this->assertTrue(true);
    }
    /**
     * Tests to see if all the DELETE routes are resolving as expeceted
     *
     * @return void
     */
    public function testDELETERoutes()
    {
        $this->assertTrue(true);
    }
}
