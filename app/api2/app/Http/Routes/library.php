<?php

use App\Library as Library;
use App\LibrarySongs as LibrarySongs;
use App\Submissions as Submission;
use App\SubmissionsSongs as SubmissionsSongs;
use App\SubmissionsArchive as Archive;
use App\TypesFormat as TypesFormat;
use Carbon\Carbon;
use Validator as Validator;

//Post to this route to write to the library edits table
Route::group(['middleware' => 'auth'], function () {
    Route::group(array('prefix'=>'library'), function () {
        Route::get('/', function () {
            //only return ids because the table is too big
            try {
                return Library::select('id')->get();
            } catch (Exception $e) {
                return $e->getMessage();
            }
        });
        Route::get('/{id}', function ($id=id) {
            if ($id == 'formats') {
                return Response::json(TypesFormat::select('id', 'name')->get());
            } else {
                $result = Library::find($id);
                $result['songs'] = Library::find($id)->songs;
                return $result;
            }
        });
        Route::post('/', function () {
            if (!is_numeric(Input::get('format'))) {
                switch (Input::get('format')) {
                    case "7i":
                        $format = 3;
                        break;
                    case "7\"":
                        $format = 3;
                        break;
                    case "??":
                        $format = 8;
                        break;
                    default:
                        $format = TypesFormat::select('id')->where('name', 'like', strtoupper(Input::get('format')))->get();
            }
            } else {
                $format = Input::get('format');
            }

            if (Input::get('playlist') == 1) {
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
        //Route to move a music submission to the library
        //Makes a library entry, an archived submissions entry, and
        //library songs entries
        Route::post('/fromsubmissions', function () {
            $submission_id = Input::get('submission_id');
            $submission = Submission::find($submission_id);

            if ($submission->playlist == 1) {
                $status = 'P';
            } else {
                $status = 'A';
            }
            try {
                $lib =  Library::create([
                    'catalog' => $submission->catalog,
                    'format_id' => $submission->format_id,
                    'status' => $status,
                    'artist' => $submission->artist,
                    'title' => $submission->title,
                    'label' => $submission->label,
                    'genre' => $submission->genre,
                    'cancon' => $submission->cancon,
                    'femcon' => $submission->femcon,
                    'local' => $submission->local,
                    'compilation' => $submission->compilation,
                    'digitized' => $submission->digitized,
                    'crtc' => $submission->crtc,
                    //TODO move album art
                    'art_url' => $submission->art_url
                ]);
            } catch (Exception $e) {
                return $e->getMessage();
            }
            try {
                $archive = Archive::create([
                    'artist' => $submission->artist,
                    'title' => $submission->title,
                    'contact' => $submission->email,
                    'label' => $submission->label,
                    'cancon' => $submission->cancon,
                    'femcon' => $submission->femcon,
                    'local' => $submission->local,
                    'description' => $submission->description,
                    'catalog' => $submission->catalog,
                    'format_id' => $submission->format_id,
                    'submitted' => $submission->submitted,
                    'review_comments' => $submission->review_comments
                ]);
            } catch (Exception $e) {
                return $e->getMessage();
            }

            if ($lib['id'] > 0) {
                foreach (SubmissionsSongs::where('submission_id', '=', $submission_id)->get() as $submission_song) {
                    try {
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
                            'compilation' => $submission['compilation'],
                            'composer' => $submission_song['composer'],
                            'crtc' => $submission['crtc'],
                            'year' => $submission_song['year'],
                            'length' => $submission_song['length'],
                            //TODO: file_location actually moves
                            'file_location' => $submission_song['file_location']
                        ]);
                    } catch (Exception $e) {
                        return $e->getMessage();
                    }
                }
                return response("Success!", 200);
            } else {
                return response("Unable to create library songs records. Please try again", 500);
            }
        });
    });
});
