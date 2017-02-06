<?php

use App\Submissions as Submissions;

//Apps inside middleware require login
//Route::group(['middleware' => 'auth'], function(){
//List all the submissions
    Route::get('/submissions', function(){
        return Response::json(Submissions::all());
    });
    //Get all of a submission's info based on the submission id
    Route::get('/submissions/{id}', function($id){
        return Response::json(Submissions::find($id));
    });

    Route::group(['prefix'=>'submissions'],function(){
        //Get list of submissions that are unreviewed
        Route::get('/bystatus/unreviewed', function(){
            $status = 'unreviewed';
            return Response::json( Submissions::where('status','=',$status)->get() );
        });
        //Get list of submissions that are reviewed
        Route::get('/bystatus/reviewed', function(){
            $status = 'reviewed';
            return Response::json( Submissions::where('status','=',$status)->get() );
        });
        //Get list of submissions that need to be tagged
        Route::get('/bystatus/tagged',function(){
            $status = 'tagged';
            return Response::json( Submissions::where('status','=',$status)->get() );
        });
        //Get list of submissions that are tagged and awaiting staff approval
        Route::get('/bystatus/approved', function(){
            $status = 'approved';
            return Response::json( Submissions::where('status','=',$status)->get() );
        });
        //Generic search ending - specify what we're looking for with 'status'
        Route::get('/search', function(){
            return;
        });
        //Post to this route to put a new submission in the system - either from manual submissions page or from the station website
        //the submission format (ie. CD, LP or MP3) defaults to MP3.
        Route::post('/', function(){
            try{
                //TODO: Maintain genre data integrity
                //require_once(dirname($_SERVER['DOCUMENT_ROOT']).'/config.php');
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
    });
//});
