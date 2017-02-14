<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Carbon\Carbon;
/**
 * Test suite to test that all routes are resolving as expected
 *
 */
class RouteExistsTest extends TestCase
{
    //Bypass the middleware for our tests
    use WithoutMiddleware;
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
                '/submissions/getaccepted'
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
    /**
     * Tests to see if all the POST routes are resolving as expeceted
     *
     * @return void
     */
    public function testPOSTRoutes()
    {
        //General form:
        // $response = $this->call('POST', '/user', ['name' => 'Taylor']);
        $response = $this->call('POST', '/submission',[
            'artist' => 'Test Artist',
            'title' => 'Test Album Title',
            'genre' => 'Talk',
            'email' => 'test@example.com',
            'label' => 'Mint Records',
            'location' => 'Vancouver',
            'credit' => 'Test Person',
            //This date is allowed to be null here, don't have to check
            'releasedate' => null,
            'cancon' => 1,
            'femcon' => 1,
            'local' => 1,
            'description' => 'Test Description',
            'art_url' => 'https://placehold.it/500x500',
            //TODO
            //'songlist' => 500,
            'format_id' => 1
        ]);
        $this->assertEquals(200, $response->getStatusCode());
    }
    /**
     * Tests to see if all the POST routes are resolving as expeceted
     *
     * @return void
     */
    public function testPOSTRoutesGarbage()
    {
        //General form:
        // $response = $this->call('POST', '/user', ['name' => 'Taylor']);
        $response = $this->call('POST', '/submission',[
            'cancon' => 1,
            'femcon' => 1,
            'local' => 1,
            'description' => 'Test Description',
            'art_url' => 'https://placehold.it/500x500',
            //TODO
            //'songlist' => 500,
            'format_id' => 1
        ]);
        $this->assertEquals(500, $response->getStatusCode());
    }
    /**
     * Tests to see if all the PUT routes are resolving as expeceted
     *
     * @return void
     */
    public function testPUTRoutes()
    {
        
    }
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
