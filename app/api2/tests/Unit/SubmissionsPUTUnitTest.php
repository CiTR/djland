<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class SubmissionsPUTUnitTest extends TestCase
{
    //Bypass the middleware for our tests
    use WithoutMiddleware;
    //Don't actually change our DB
    use DatabaseTransactions;
    
    /**
     * Tests to see if all the PUT routes are resolving as expeceted
     *
     * @return void
     */
    public function testPUTRoutes()
    {
        $response = $this->call('PUT', '/submissions/review',[
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
}
