<?php

use App\Library as Library;
use App\LibrarySongs as LibrarySongs;
use App\Submision as Submission;
use App\SubmissionsSongs as SubmissionsSongs;
use Carbon\Carbon;
use Validator as Validator;

//Post to this route to write to the library edits table
//Route::group(['middleware' => 'auth'], function(){
    Route::group(array('prefix'=>'library'), function(){
        Route::get('/',function(){
            //only return ids because the table is too big
            try{
                return Library::select('id')->get();
            } catch(Exception $e){
                return $e->getMessage();
            }
        });
        Route::get('/{id}',function($id=id){
            $result = Library::find($id);
            $result['songs'] = Library::find($id)->songs;
            return $result;
        });
        Route::post('/',function(){
            if(!is_numeric(Input::get('format')))
                switch(Input::get('format')){
                    //TODO: look this up in DB
                    case "CD":
                        $format = 1;
                        break;
                    case "cd":
                        $format = 1;
                        break;
                    case "LP":
                        $format = 2;
                        break;
                    case "7i":
                        $format = 3;
                        break;
                    case "7\"":
                        $format = 3;
                        break;
                    case "CASS":
                        $format = 4;
                        break;
                    case "CART":
                        $format = 5;
                        break;
                    case "MP3":
                        $format = 6;
                        break;
                    case "mp3":
                        $format = 6;
                        break;
                    case "mP3":
                        $format = 6;
                        break;
                    case "Mp3":
                        $format = 6;
                        break;
                    case "MD":
                        $format = 7;
                        break;
                    case "??":
                        $format = 8;
                        break;
                    default:
                        $format = 8;
            } else {
                $format = Input::get('format');
            }

            if(Input::get('playlist') == 1){
                $status = 'P';
            } else {
                $status = 'A';
            }

            return Library::create([
                'catalog' => Input::get('catalog'),
                'format_id' => $format,
                'status' => $status,
                'artist' => Input::get('artist'),
                'title' => Input::get('album_title'),
                'label' => Input::get('label'),
                'genre' => Input::get('genre'),
                'cancon' => Input::get('cancon'),
                'femcon' => Input::get('femcon'),
                'local' => Input::get('local'),
                'compilation' => Input::get('compilation'),
                'digitized' => Input::get('in_sam')
            ]);
        });
        Route::post('/fromsubmissions',function(){
            $submission_id = Input::get('submission_id');
            if(!is_numeric(Input::get('format')))
                switch(Input::get('format')){
                    //TODO: look this up in DB
                    case "CD":
                        $format = 1;
                        break;
                    case "cd":
                        $format = 1;
                        break;
                    case "LP":
                        $format = 2;
                        break;
                    case "7i":
                        $format = 3;
                        break;
                    case "7\"":
                        $format = 3;
                        break;
                    case "CASS":
                        $format = 4;
                        break;
                    case "CART":
                        $format = 5;
                        break;
                    case "MP3":
                        $format = 6;
                        break;
                    case "mp3":
                        $format = 6;
                        break;
                    case "mP3":
                        $format = 6;
                        break;
                    case "Mp3":
                        $format = 6;
                        break;
                    case "MD":
                        $format = 7;
                        break;
                    case "??":
                        $format = 8;
                        break;
                    default:
                        $format = 8;
            } else {
                $format = Input::get('format');
            }

            if(Input::get('playlist') == 1){
                $status = 'P';
            } else {
                $status = 'A';
            }

            $lib =  Library::create([
                'catalog' => Input::get('catalog'),
                'format_id' => $format,
                'status' => $status,
                'artist' => Input::get('artist'),
                'title' => Input::get('album_title'),
                'label' => Input::get('label'),
                'genre' => Input::get('genre'),
                'cancon' => Input::get('cancon'),
                'femcon' => Input::get('femcon'),
                'local' => Input::get('local'),
                'compilation' => Input::get('compilation'),
                'digitized' => Input::get('in_sam'),
                'crtc' => Input::get('crtc')
            ]);
            if($lib['id'] > 0){
                foreach(SubmissionsSongs::where('submission_id', '=', $submission_id)->get() as $submission_song){
                    LibrarySongs::create([
                        'library_id' => $lib['id'],
                        'artist' => $submission_song['artist'],
                        'album_artist' => $submission_song['album_artist'],
                        'song_title' => $submission_song['song_title'],
                        'album_title' => $submission_song['album_title'],
                        'credit' => $submission_song['credit'],
                        'track_num' => $submission_song['track_num'],
                        'tracks_total' => $submission_song['tracks_total'],
                        'genre' => $submission_song['genre'],
                        's/t' => $submission_song['s/t'],
                        'v/a' => $submission_song['v/a'],
                        'compilation' => Input::get('compilation'),
                        'composer' => $submission_song['composer'],
                        'crtc' => Input::get('crtc'),
                        'year' => $submission_song['year'],
                        'length' => $submission_song['length'],
                        //TODO: file_location
                        'file_location' => ""
                    ]);
                    $i++;
                }
                return "Success!";
            }else{
                return response("Unable to create library record. Please try again", 500);
            }
        });
    });
//});
