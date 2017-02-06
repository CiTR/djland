<?php

use App\Submissions as Submissions;
use App\Submissions_Archive as Archive;
use App\Submissions_Rejected as Rejected;

//Apps inside middleware require login
Route::group(['middleware' => 'auth'], function(){
    Route::group(['prefix' => 'submission'], function(){
        //List all the submissions
        Route::get('/', function(){
            return Response::json(Submissions::all());
        });
        //Get all of a submission's info based on the submission id
        Route::get('/{id}', function($id){
            return Response::json(Submissions::find($id));
        });
        //Get list of submissions that are unreviewed
        Route::get('/unreviewed', function(){
        });
        //Get list of submissions that are reviewed
        Route::get('/reviewed', function(){

        });
        //Get list of submissions that need to be tagged
        Route::get('/approved', function(){
        });
        //Get list of submissions that are tagged and awaiting staff approval
        Route::get('/tagged', function(){
        });
        //Generic search ending - specify what we're looking for with 'status'
        Route::get('/search', function(){
            return;
        });
        // TODO: Search past archived submissions
        Route::get('/archived', function(){
            return;
        });
        //Post to this route to put a new submission in the system - either from manual submissions page or from the station website
        //the submission format (ie. CD, LP or MP3) defaults to MP3.
        Route::post('/', function(){
            try{
                //TODO: Maintain genre data integrity
                require_once(dirname($_SERVER['DOCUMENT_ROOT']).'/config.php');
                //foreach($primary_genres as $genre) {
                //    if(Input::get('genre') == $genre){
                        $ingenre = Input::get('genre');
                //    } else {
                //        return "Invalid genre specified";
                //    }
                    $id = Submission::create([
                        'artist' => Input::get('artist'),
                        'title' => Input::get('title'),
                        'genre' => $ingenre,
                        'email' => Input::get('email'),
                        'label' => Input::get('label'),
                        'location' => Input::get('location'),
                        'credit' => Input::get('credit'),
                        'releasedate' => Input::get('releasedate'),
                        'cancon' => Input::get('cancon'),
                        'femcon' => Input::get('femcon'),
                        'local' => Input::get('local'),
                        'description' => Input::get('description'),
                        'art_url' => Input::get('art_url'),
                        'songlist' => Input::get('songlist'),
                        'format_id' => Input::get('format_id'),
                        'status' => 'unreviewed'
                    ]);
                return $id;

            } catch(Exception $e){
                return $e->getMessage();
            }
        });
        //Post to this route when you've reviewed a new submisison
        Route::post('/review', function(){

        });
        //Post to this route when staff approve a reviewed submisison
        Route::post('/approve', function(){

        });
        //Post to this route when a user has tagged a submission
        Route::post('/tag', function(){

        });
        //Post to this route when staff approve tags and send submission to library
        Route::post('/tolibrary', function(){

        });
        //TODO: Post to this route to reject a submission
        Route::post('/reject', function(){

        });
        //TODO: Post to this route to restore a rejected submission
        Route::post('/restore', function(){

        });
        //TODO: Post to this route to find new digital submissions
        Route::post('/getnew', function(){

        });
    });
});
