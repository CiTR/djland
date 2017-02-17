<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class SubmissionsSecurityTest extends TestCase
{
    /**
     * Test Security of Submissions route -
     * will try and do things without logging in or being at the
     * minimum required permission level
     *
     * @return void
     */
    public function testSecurity()
    {

    }
}
