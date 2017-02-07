<?php

use App\Submissions as Submissions;
use App\Submissions_Archive as Archive;
use App\Submissions_Rejected as Rejected;
use Carbon\Carbon;

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
    //Post to this route to put a new submission in the system - either from manual submissions page or from the station website
    //the submission format (ie. CD, LP or MP3) defaults to MP3.
    Route::post('/submission', function(){
        try{
            //TODO: Maintain genre data integrity
            //require_once(dirname($_SERVER['DOCUMENT_ROOT']).'/config.php');
            //foreach($primary_genres as $genre) {
            //    if(Input::get('genre') == $genre){
                    $ingenre = Input::get('genre');
            //    } else {
            //        return "Invalid genre specified";
            //    }
                $id = Submissions::create([
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
                    'status' => 'unreviewed',
                    'submitted' => Carbon::today()->toDateString()
                ]);
            return $id;

        } catch(Exception $e){
            return $e->getMessage();
        }
    });
    Route::group(['prefix'=>'submissions'],function(){
        //Get list of submissions that are unreviewed
        Route::get('/bystatus/unreviewed/', function(){
            $status = 'unreviewed';
            return Response::json( Submissions::where('status','=',$status)->where('format_id','=',1)->get() );
        });
        //Get list of submissions that are unreviewed and are CD format
        Route::get('/bystatus/unreviewed/cd', function(){
            $status = 'unreviewed';
            return Response::json( Submissions::where('status','=',$status)->where('format_id','=',1)->get() );
        });
        //Get list of submissions that are unreviewed and are MP3 format
        Route::get('/bystatus/unreviewed/mp3', function(){
            $status = 'unreviewed';
            return Response::json( Submissions::where('status','=',$status)->where('format_id','=',6)->get() );
        });
        //Get list of submissions that are unreviewed and are any other format
        Route::get('/bystatus/unreviewed/other', function(){
            $status = 'unreviewed';
            return Response::json( Submissions::where('status','=',$status)->where('format_id','!=',1)->where('format_id','!=',6)->get() );
        });
        //Get list of submissions that are reviewed but the submission is not approved
        Route::get('/bystatus/reviewed', function(){
            $status = 'reviewed';
            return Response::json( Submissions::where('status','=',$status)->get() );
        });
        //Get list of submissions that are reviewed but the submission is not approved and are cds
        Route::get('/bystatus/reviewed/cd', function(){
            $status = 'reviewed';
            return Response::json( Submissions::where('status','=',$status)->where('format_id','=',1)->get() );
        });
        //Get list of submissions that are reviewed but the submission is not approved and are mp3s
        Route::get('/bystatus/reviewed/mp3', function(){
            $status = 'reviewed';
            return Response::json( Submissions::where('status','=',$status)->where('format_id','=',6)->get() );
        });
        //Get list of submissions that are reviewed but the submission is not approved and are any other format
        Route::get('/bystatus/reviewed/other', function(){
            $status = 'reviewed';
            return Response::json( Submissions::where('status','=',$status)->where('format_id','!=',1)->where('format_id','!=',6)->get() );
        });
        //Get list of submissions that need to be tagged
        Route::get('/bystatus/tagged',function(){
            $status = 'tagged';
            return Response::json( Submissions::where('status','=',$status)->get() );
        });
        //Get list of submissions that need to be tagged and are cds
        Route::get('/bystatus/tagged/cd',function(){
            $status = 'tagged';
            return Response::json( Submissions::where('status','=',$status)->where('format_id','=',1)->get() );
        });
        //Get list of submissions that need to be tagged and are mp3s
        Route::get('/bystatus/tagged/mp3',function(){
            $status = 'tagged';
            return Response::json( Submissions::where('status','=',$status)->where('format_id','=',6)->get() );
        });
        //Get list of submissions that need to be tagged and are in any other format
        Route::get('/bystatus/tagged/other',function(){
            $status = 'tagged';
            return Response::json( Submissions::where('status','=',$status)->where('format_id','!=',1)->where('format_id','!=',6)->get() );
        });
        //Get list of reviewed submissions that are approved and need to be tagged
        Route::get('/bystatus/approved', function(){
            $status = 'approved';
            return Response::json( Submissions::where('status','=',$status)->get() );
        });
        //Get list of reviewed submissions that are approved and need to be tagged and are cds
        Route::get('/bystatus/approved/cd', function(){
            $status = 'approved';
            return Response::json( Submissions::where('status','=',$status)->where('format_id','=',1)->get() );
        });
        //Get list of reviewed submissions that are approved and need to be tagged and are mp3s
        Route::get('/bystatus/approved/mp3', function(){
            $status = 'approved';
            return Response::json( Submissions::where('status','=',$status)->where('format_id','=',6)->get() );
        });
        //Get list of reviewed submissions that are approved and need to be tagged and are in any other format
        Route::get('/bystatus/approved/other', function(){
            $status = 'approved';
            return Response::json( Submissions::where('status','=',$status)->where('format_id','!=',1)->where('format_id','!=',6)->get() );
        });
        Route::get('/bystatus/trashed', function(){
            $status = 'trashed';
            return Response::json( Submissions::where('status','=',$status)->get() );
        });
        //Generic search ending - specify what we're looking for with 'status'
        Route::get('/search', function(){
            return;
        });
        // TODO: Search past archived and rejected submissions
        Route::get('/searchpast', function(){

        });
        //TODO: Search accepted digital submissions in a time range
        Route::get('/getaccepted', function(){

        });
        //Post to this route when a user reviews a new submisison
        Route::put('/review', function(){

        });
        //Post to this route when staff approve a review for a submisison
        Route::put('/approve', function(){

        });
        //Post to this route when a user has tagged a submission
        Route::put('/tag', function(){

        });
        //Post to this route when staff approve tags and send submission to library
        Route::put('/tolibrary', function(){

        });
        //TODO: Post to this route to reject a submission
        Route::put('/reject', function(){

        });
        //TODO: Post to this route to restore a rejected submission
        Route::put('/restore', function(){

        });
    });
//});
