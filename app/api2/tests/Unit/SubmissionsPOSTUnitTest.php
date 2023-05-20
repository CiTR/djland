<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class SubmissionsPOSTUnitTest extends TestCase
{
    //Bypass the middleware for our tests
    use WithoutMiddleware;
    //Don't actually change our DB
    use DatabaseTransactions;

    /**
      * Tests to see if all the POST routes are resolving as expeceted
      * optional variables not included
      *
      * @return void
      */
    public function testMinimalPOSTRoutes()
    {
        $response = $this->call('POST', '/submission',[
            'artist' => 'Artist -_/~!@#$&*\\',
            'title' => 'Title -_/~!@#$&*\\',
            'genre' => 'Talk',
            'email' => 'me@example.com',
            'location' => 'Vancouver -_/~!@#$&*\\',
            'label' => 'Mint Records -_/~!@#$&*\\', // / Test & More -_~!@#$&*",
            //This date is allowed to be null here, don't have to check
            //'releasedate' => '2006-10-10',
            'cancon' => 1,
            'femcon' => 1,
            'local' => 1,
            //'description' => 'Test -_/~!@#$&*\\',
            //'art_url' => 'https://placehold.it/500x500',
            //TODO
            //'songlist' => 500,
            'format_id' => 1
        ]);
        $this->assertEquals(200, $response->getStatusCode());
    }
    /**
      * Tests to see if all the POST routes are resolving as expeceted
      * all optional variables are included
      *
      * @return void
      */
     public function testMaximalPOSTRoutes()
     {

        $response = $this->call('POST', '/submission',[
            'artist' => 'Artist -_/~!@#$&*\\',
            'title' => 'Title -_/~!@#$&*\\',
            'genre' => 'Talk',
            'email' => 'me@example.com',
            'location' => 'Vancouver -_/~!@#$&*\\',
            'label' => 'Mint Records -_/~!@#$&*\\',
            //This date is allowed to be null here, don't have to check
            //'releasedate' => '2006-10-10',
            'cancon' => 1,
            'femcon' => 1,
            'local' => 1,
            'description' => 'Test -_/~!@#$&*\\',
            //'art_url' => 'https://placehold.it/500x500',
            //TODO
            //'songlist' => 500,
            'format_id' => 1
        ]);
        $this->assertEquals(200, $response->getStatusCode());
    }
    /**
      * Test to see if POST routes without required fields
      * return a 422 response as expeceted
      *
      * @return void
      */
    public function testPOSTRoutesWithoutFields(){
        $response = $this->call('POST', '/submission',[
            'cancon' => 1,
            'femcon' => 1,
            'local' => 1,
            'description' => 'Test garbage Description',
            'art_url' => 'https://google.com',
            //TODO
            //'songlist' => 500,
            'format_id' => 8
        ]);
        $this->assertEquals(422, $response->getStatusCode());
    }

    // Try the entire text of Hamlet or something stupid in a text field
    // Try mani non-ascii chars
    public function testPOSTRoutesWithIllegalFields(){

    }
}
