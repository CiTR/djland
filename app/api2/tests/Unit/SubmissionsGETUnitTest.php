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
    *
     * Tests to see if all the GET routes are resolving as expeceted
     *
     * @return void
     */
    public function testSubmissionsGETRoutesJSON()
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
            //Uncomment to see which of the above routes this test fails on
            //echo $route;
            $response = $this->call('GET',$route);
            $this->assertEquals(200, $response->getStatusCode());
            $this->visit($route)
                ->seeJSON();
        }
    }
    /**
     * Tests to see if GET routes with garbage parameters
     * return the expected response
     *
     * @return void
     */
    public function testSubmissionsGETRoutesGarbage()
    {
        $response = $this->call('GET', '/submissions/-1');
        $this->assertEquals(422, $response->getStatusCode());
        $response = $this->call('GET', '/submissions/100000000000000000');
        $this->assertEquals(200, $response->getStatusCode());
        $this->seeJSON();
        $response = $this->call('GET', '/submissions/a');
        $this->assertEquals(422, $response->getStatusCode());
    }
    /**
     * Tests to see if GET routes return the correct data fields
     *
     * @return void
     */
    public function testSubmissionsGETRoutesKeyValueAllSubmissions(){
        //TODO: fill database with something so that it's not empty
        $response = $this->call('GET', 'submissions/');
        $expectedKeyValue = array(
            'id' => integer,
            ''
        );
        foreach($response as $item){
            foreach($expectedKeyValue as $key){
                //Test that the key is as expected
                assertEquals(keyOf($response[$item][$key]) == $key);
                //Assert that the value of that key is of the expected data type
                assertEquals(typeOf($response[$item[$key]) == typeOf($expectedKeyValue[$key]));
            }
        }
    }
    /**
     * Tests to see if GET routes return the correct data fields
     *
     * @return void
     */
    public function testSubmissionsGETRoutesKeyValueByID(){
        //TODO: fill database with something so that it's not empty
    }
    /**
     * Tests to see if GET routes return the correct data fields
     *
     * @return void
     */
    public function testSubmissionsGETRoutesKeyValueByStatusUnreviewed(){
        //TODO: fill database with something so that it's not empty
    }
    /**
     * Tests to see if GET routes return the correct data fields
     *
     * @return void
     */
    public function testSubmissionsGETRoutesKeyValueByStatusReviewed(){
        //TODO: fill database with something so that it's not empty
    }
    /**
     * Tests to see if GET routes return the correct data fields
     *
     * @return void
     */
    public function testSubmissionsGETRoutesKeyValueByStatusApproved(){
        //TODO: fill database with something so that it's not empty
    }
    /**
     * Tests to see if GET routes return the correct data fields
     *
     * @return void
     */
    public function testSubmissionsGETRoutesKeyValueByStatusTagged(){
        //TODO: fill database with something so that it's not empty
    }
    /**
     * Tests to see if GET routes return the correct data fields
     *
     * @return void
     */
    public function testSubmissionsGETRoutesKeyValueByStatusCompleted(){
        //TODO: fill database with something so that it's not empty
    }
}
