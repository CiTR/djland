<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class SubmissionsGETUnitTest extends TestCase
{
    //Bypass the middleware for our tests
    use WithoutMiddleware;
    //Don't actually change our DB
    use DatabaseTransactions;

    /**
     * Tests to see if all the GET routes are resolving as expeceted
     *
     * @return void
     */
    public function testGETRoutesJSON()
    {
        $routes=['/submissions',
                '/submissions/5',
                '/submissions/50',
                '/submissions/bystatus/unreviewed',
                '/submissions/bystatus/unreviewed/cd',
                '/submissions/bystatus/unreviewed/mp3',
                '/submissions/bystatus/unreviewed/other',
                '/submissions/bystatus/reviewed',
                '/submissions/bystatus/reviewed/cd',
                '/submissions/bystatus/reviewed/mp3',
                '/submissions/bystatus/reviewed/other',
                '/submissions/bystatus/tagged',
                '/submissions/bystatus/tagged/cd',
                '/submissions/bystatus/tagged/mp3',
                '/submissions/bystatus/tagged/other',
                '/submissions/bystatus/approved',
                '/submissions/bystatus/approved/cd',
                '/submissions/bystatus/approved/mp3',
                '/submissions/bystatus/approved/other',
                '/submissions/bystatus/trashed',
                //'/submissions/getaccepted'
                ];
        foreach($routes as $route){
            $response = $this->call('GET',$route);
            $this->assertEquals(200, $response->getStatusCode());
            $this->visit($route)
                ->seeJSON();
        }
    }
    /**
     * Tests to see if GET routes with garbage parameters return 500 errors
     *
     * @return void
     */
    public function testGETRoutesGarbage()
    {
        $response = $this->call('GET', '/submissions/-1');
        $this->assertEquals(500, $response->getStatusCode());
        $response = $this->call('GET', '/submissions/100000000000000000');
        $this->assertEquals(500, $response->getStatusCode());
        $response = $this->call('GET', '/submissions/a');
        $this->assertEquals(500, $response->getStatusCode());
    }
}
