<?php

use App\Submissions as Submissions;
use App\Submissions_Archive as Archive;
use App\Submissions_Rejected as Rejected;
use Carbon\Carbon;

//Post to this route to put a new submission in the system - either from manual submissions page or from the station website
//the submission format (ie. CD, LP or MP3) defaults to MP3.
Route::post('/submission', function(){
    try{
        //TODO: track songlist properly (new table?)
        $songlist_id = 0;
        //TODO: Maintain genre data integrity
        //require_once(dirname($_SERVER['DOCUMENT_ROOT']).'/config.php');
        //foreach($primary_genres as $genre) {
        //    if(Input::get('genre') == $genre){
                $ingenre = Input::get('genre');
        //    } else {
        //        return "Invalid genre specified";
        //    }
        //Default to "Self released" if the label is not specified
        if(Input::get('label') == null){
            $label = "Self-released";
        } else{
            $label = Input::get('label');
        }
        $newsubmission = Submissions::create([
            'artist' => Input::get('artist'),
            'title' => Input::get('title'),
            'genre' => $ingenre,
            'email' => Input::get('email'),
            'label' => $label,
            'location' => Input::get('location'),
            'credit' => Input::get('credit'),
            //This date is allowed to be null here, don't have to check
            'releasedate' => Input::get('releasedate'),
            'cancon' => Input::get('cancon'),
            'femcon' => Input::get('femcon'),
            'local' => Input::get('local'),
            'playlist' => 0,
            'compilation' => 0,
            'digitized' => 0,
            'description' => Input::get('description'),
            'art_url' => Input::get('art_url'),
            'songlist' => $songlist_id,//Input::get('songlist'),
            'format_id' => Input::get('format_id'),
            'status' => 'unreviewed',
            'submitted' => Carbon::today()->toDateString(),
            'is_trashed' => 0,
            'staff_comment' => "",
            'review_comments' => "",
            //TODO: determine what we're doing with this column
            'crtc' => "20"
        ]);
        return $newsubmission;
    } catch(Exception $e){
        return $e->getMessage();
    }
});

//Apps inside middleware require login
Route::group(['middleware' => 'auth'], function(){
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
        Route::get('/bystatus/unreviewed/', function(){
            $status = 'unreviewed';
            return Response::json( Submissions::where('status','=',$status)->where('is_trashed', '=', 0)->get() );
        });
        //Get list of submissions that are unreviewed and are CD format
        Route::get('/bystatus/unreviewed/cd', function(){
            $status = 'unreviewed';
            return Response::json( Submissions::where('status','=',$status)->where('format_id','=',1)->where('is_trashed', '=', 0)->get() );
        });
        //Get list of submissions that are unreviewed and are MP3 format
        Route::get('/bystatus/unreviewed/mp3', function(){
            $status = 'unreviewed';
            return Response::json( Submissions::where('status','=',$status)->where('format_id','=',6)->where('is_trashed', '=', 0)->get() );
        });
        //Get list of submissions that are unreviewed and are any other format
        Route::get('/bystatus/unreviewed/other', function(){
            $status = 'unreviewed';
            return Response::json( Submissions::where('status','=',$status)->where('format_id','!=',1)->where('format_id','!=',6)->where('is_trashed', '=', 0)->get() );
        });
        //Get list of submissions that are reviewed but the submission is not approved
        Route::get('/bystatus/reviewed', function(){
            $status = 'reviewed';
            return Response::json( Submissions::where('status','=',$status)->where('is_trashed', '=', 0)->get() );
        });
        //Get list of submissions that are reviewed but the submission is not approved and are cds
        Route::get('/bystatus/reviewed/cd', function(){
            $status = 'reviewed';
            return Response::json( Submissions::where('status','=',$status)->where('format_id','=',1)->where('is_trashed', '=', 0)->get() );
        });
        //Get list of submissions that are reviewed but the submission is not approved and are mp3s
        Route::get('/bystatus/reviewed/mp3', function(){
            $status = 'reviewed';
            return Response::json( Submissions::where('status','=',$status)->where('format_id','=',6)->where('is_trashed', '=', 0)->get() );
        });
        //Get list of submissions that are reviewed but the submission is not approved and are any other format
        Route::get('/bystatus/reviewed/other', function(){
            $status = 'reviewed';
            return Response::json( Submissions::where('status','=',$status)->where('format_id','!=',1)->where('format_id','!=',6)->where('is_trashed', '=', 0)->get() );
        });
        //Get list of submissions that need to be tagged
        Route::get('/bystatus/tagged',function(){
            $status = 'tagged';
            return Response::json( Submissions::where('status','=',$status)->where('is_trashed', '=', 0)->get() );
        });
        //Get list of submissions that need to be tagged and are cds
        Route::get('/bystatus/tagged/cd',function(){
            $status = 'tagged';
            return Response::json( Submissions::where('status','=',$status)->where('format_id','=',1)->where('is_trashed', '=', 0)->get() );
        });
        //Get list of submissions that need to be tagged and are mp3s
        Route::get('/bystatus/tagged/mp3',function(){
            $status = 'tagged';
            return Response::json( Submissions::where('status','=',$status)->where('format_id','=',6)->where('is_trashed', '=', 0)->get() );
        });
        //Get list of submissions that need to be tagged and are in any other format
        Route::get('/bystatus/tagged/other',function(){
            $status = 'tagged';
            return Response::json( Submissions::where('status','=',$status)->where('format_id','!=',1)->where('format_id','!=',6)->where('is_trashed', '=', 0)->get() );
        });
        //Get list of reviewed submissions that are approved and need to be tagged
        Route::get('/bystatus/approved', function(){
            $status = 'approved';
            return Response::json( Submissions::where('status','=',$status)->where('is_trashed', '=', 0)->get() );
        });
        //Get list of reviewed submissions that are approved and need to be tagged and are cds
        Route::get('/bystatus/approved/cd', function(){
            $status = 'approved';
            return Response::json( Submissions::where('status','=',$status)->where('format_id','=',1)->where('is_trashed', '=', 0)->get() );
        });
        //Get list of reviewed submissions that are approved and need to be tagged and are mp3s
        Route::get('/bystatus/approved/mp3', function(){
            $status = 'approved';
            return Response::json( Submissions::where('status','=',$status)->where('format_id','=',6)->where('is_trashed', '=', 0)->get() );
        });
        //Get list of reviewed submissions that are approved and need to be tagged and are in any other format
        Route::get('/bystatus/approved/other', function(){
            $status = 'approved';
            return Response::json( Submissions::where('status','=',$status)->where('format_id','!=',1)->where('format_id','!=',6)->where('is_trashed', '=', 0)->get() );
        });
        Route::get('/bystatus/trashed', function(){
            return Response::json( Submissions::where('is_trashed', '=', 1)->get() );
        });
        //Generic search ending - specify what we're looking for with 'status', 'format' etc
        Route::get('/search', function(){
            return;
        });
        // Search accepted digital submissions in a time range
        Route::get('/getaccepted', function(){
            $date = Input::get('date');
            return Response::json( Archive::where('submitted','>',$date)->where('submitted','<',$date)->get() );
        });
        // TODO: Search past submissions (rejected & archived) on admins page
        Route::get('/getrejectedandarchived', function(){
            return;
        });
        // TODO: Search past rejected submissions
        Route::get('/rejected', function(){
            return;
        });
        //Post to this route when a user reviews a new submisison
        //Requires: id (ie. submission id), review_comments(text), and approved (0 or 1)
        Route::put('/review', function(){
            try{
                $submission = Submissions::find(Input::get('id'));
                if($submission -> is_trashed == 1) return "Trying to review a submission that is in the trash. Aborting. Submission id is: " . $submission -> id;
                else if($submission -> status != "unreviewed") return "Trying to review a submission that is already been reviewed. Aborting. Submission id is: " . $submission -> id;
                else {
                    $submission -> status = "reviewed";
                    $submission -> review_comments = Input::get('review_comments');
                    $approval = Input::get('approved');
                    if($approval == 'yes' || $approval == 'Yes' || $approval == 1 ) $submission -> approved = 1;
                    else $submission -> approved = 0;
                    //Save the member id based on who submitted
                    $submission -> reviewed = $_SESSION['sv_id'];
                    $submission->save();
                    return Response::json("Update submission #" . $submission -> id . " from unreviewd to reviewed successful");
                }
            } catch (Exception $e){
                return $e->getMessage() ;
            }
        });
        //Post to this route when staff approve a review for a submisison
        //Requires: id (ie. submission id) , and other stuff tbd
        Route::put('/approve', function(){
            try{
                $submission = Submissions::find(Input::get('id'));
                if($submission -> is_trashed == 1) return "Trying to approve a review of a submission that is in the trash. Aborting. Submission id is: " . $submission -> id;
                else if($submission -> status == "unreviewed") return "Trying to appprove a review of a submission that hasn't been reviewed yet. Aborting. Submission id is: " . $submssion -> id;
                else if($submission -> status != "reviewed") return "Trying to approve a review of a submission that is already been approved. Aborting. Submission id is: " . $submission -> id;
                else{
                    $submission = Submissions::find(Input::get('id'));
                    $submission -> status = "approved";
                    $submission->save();
                    return Response::json("Update submission #" . $submission -> id . " from reviewed to approved successful" );
                }
            } catch (Exception $e){
                return $e->getMessage();
            }
        });
        //Post to this route when a user has tagged a submission
        Route::put('/tag', function(){
            try{
                $submission = Submissions::find(Input::get('id'));
                if($submission -> is_trashed == 1) return "Trying to tag a submission that is in the trash. Aborting. Submission id is: " . $submission -> id;
                else if($submission -> status == "unreviewed") return "Trying to tag a review of a submission that hasn't been reviewed yet. Aborting. Submission id is: " . $submssion -> id;
                else if($submission -> status == "reviewed") return "Trying to tag a review of a submission that hasn't been approved yet. Aborting. Submission id is: " . $submission -> id;
                else if($submission -> status != "approved") return "Trying to tag a review of a submission that hs already been tagged. Aborting. Submission id is: " . $submission -> id;
                else{
                    $submission = Submissions::find(Input::get('id'));
                    $submission -> status = "tagged";
                    $newTag = Input::get('tags');
                    $submission -> tags = $newTag;
                    $submission -> catalog = Input::get('catalog');
                    $submission -> format_id = Input::get('format_id');
                    $submission -> title = Input::get('title');
                    $submission -> artist = Input::get('artist');
                    $submission -> credit = Input::get('credit');
                    $submission -> label = Input::get('label');
                    $submission -> genre = Input::get('genre');

                    $submission->save();
                    return Response::json("Update submission #" . $submission -> id . " from approved to tagged successful" );
                    return $submission;
                }
            } catch (Exception $e){
                return $e->getMessage();
            }
        });
        //Post to this route when staff approve tags and send submission to library
        Route::put('/tolibrary', function(){
            try{
                $submission = Submissions::find(Input::get('id'));
                if($submission -> is_trashed == 1) return "Trying to send a submission to the library that is in the trash. Aborting. Submission id is: " . $submission -> id;
                else if($submisison -> status == "unreviewed") return "Trying to send a submission to the library that hasn't been reviewed yet. Aborting. Submission id is: " . $submssion -> id;
                else if($submission -> status == "reviewed") return "Trying to send a submission to the library that hasn't been approved yet. Aborting. Submission id is: " . $submission -> id;
                else if($submission -> status == "approved") return "Trying to send a submission to the library that has not been tagged. Aborting. Submission id is: " . $submission -> id;
                else if($submission -> status != "tagged") return "Trying to send a submission to the library that has already been tagged and sent to library. Aborting. Submission id is: " . $submission -> id;
                else{
                    $submission = Submissions::find(Input::get('id'));
                    $submission -> status = "completed";
                    //TODO: anything else necessary (is there anything? check if any tags have changed?)
                    $submission->save();
                    return Response::json("Update submission #" . $submission -> id . " from approved to tagged successful" );
                    return $submission;
                }
            } catch (Exception $e){
                return $e->getMessage();
            }
        });
        // Post to this route to reject a submission
        Route::put('/reject', function() {
          try {
              $submission = Submissions::find(Input::get('id'));
              $submission -> is_trashed = 1;
              $submission->save();
              return Response::json("Update submission #" . $submission -> id . " to trashed");

          } catch (Exception $e) {
              return $e->getMessage() ;
          }
        });
        // Post to this route to restore a rejected submission
        Route::put('/restore', function() {
          try {
              $submission = Submissions::find(Input::get('id'));
              $submission -> is_trashed = 0;
              $submission->save();
              return Response::json("Update submission #" . $submission -> id . " from trashed to restored");

          } catch (Exception $e) {
              return $e->getMessage() ;
          }
        });
    });
});
