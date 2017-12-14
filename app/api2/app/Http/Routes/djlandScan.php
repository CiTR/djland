<?php

use App\SubmissionsSongs;
use App\LibrarySongs;
use Symfony\Component\Process\Process as Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

//Route::group(['middleware' => 'auth'], function () {
    Route::group(['prefix' =>'/djlandscan'], function () {
        //Get a list of potential scan actions
        Route::get('/generatescanresults', function () {
            try {
                //Python3 must be installed and in PATH for this to work
                // TODO: Convert DJLand scan to all php
                $process = new Process("python3 " . realpath($_SERVER['DOCUMENT_ROOT'] . "/tools/djland_scan/djland_scan.py"));
                $process->run();

                // executes after the command finishes
                if (!$process->isSuccessful()) {
                    throw new ProcessFailedException($process);
                }
                return($process->getOutput());
            } catch (Exception $e) {
                return $e->getMessage();
            }
        });
        Route::post('/doimport', function () {
            $actions = Input::get('actions');
            //Keep track of what happened in order to return a report to the user
            $actionsTaken = array();
            //Using a python script to do the moving, could do this just with php

            if (is_array($actions) || is_object($actions)) {
                foreach ($actions as $key => $action) {
                    //Step one: add to sql table
                    $sqlStatus = false;
                    try {
                        if ($action['sql']['table'] == "submissions") {
                            $sqlId = SubmissionsSongs::create([
                                'submission_id' => $action['sql']['vals']['this_id'],
                                'artist' => $action['sql']['vals']['artist'],
                                'album_artist' => $action['sql']['vals']['albumartist'],
                                'album_title' => $action['sql']['vals']['album_title'],
                                'song_title' => $action['sql']['vals']['song_title'],
                                'track_num' => $action['sql']['vals']['track_num'],
                                'genre' => $action['sql']['vals']['genre'],
                                'compilation' => $action['sql']['vals']['compilation'],
                                'crtc' => $action['sql']['vals']['category'],
                                'year' => $action['sql']['vals']['year'],
                                'length' => $action['sql']['vals']['length'],
                                'file_location' => $action['sql']['vals']['dest_filename']
                            ]);
                            $sqlStatus = true;
                        } elseif ($action['sql']['table'] == "library") {
                            $sqlId = LibrarySongs::create([
                                'submission_id' => $action['sql']['vals']['this_id'],
                                'artist' => $action['sql']['vals']['artist'],
                                'album_artist' => $action['sql']['vals']['albumartist'],
                                'album_title' => $action['sql']['vals']['album_title'],
                                'song_title' => $action['sql']['vals']['song_title'],
                                'track_num' => $action['sql']['vals']['track_num'],
                                'genre' => $action['sql']['vals']['genre'],
                                'compilation' => $action['sql']['vals']['compilation'],
                                'crtc' => $action['sql']['vals']['category'],
                                'year' => $action['sql']['vals']['year'],
                                'length' => $action['sql']['vals']['length'],
                                'file_location' => $action['sql']['vals']['dest_filename']
                            ]);
                            $sqlStatus = true;
                        } elseif ($action['sql']['table'] == "manual") {
                            //Don't create sql entries
                            $sqlStatus = true;
                        }
                    } catch (Exception $e) {
                        $sqlStatus = false;
                        //return $e->getMessage();
                    }

                    $fileStatus = false;
                    if ($sqlStatus) {
                        try {
                            //Step two: move file if a sql entry is made
                            makedirsCustom(dirname($action['destFilename']));
                            $fileStatus = rename($action['sourceFilename'], $action['destFilename']);
                        } catch (Exception $e) {
                            $filestatus=false;
                        }
                    }

                    if ($fileStatus && $sqlStatus) {
                        $actionsTaken[] = "The file " . $action['sourceFilename'] . " was moved to " . $action['destFilename'] . " and successfully added to the " . $action['sql']['table'] . " database";
                    } elseif (!$fileStatus && $sqlStatus) {
                        $actionsTaken[] = "There was a problem moving the file from " . $action['sourceFilename'] . " to " . $action['destFilename'] . " but was successfully added to the " . $action['sql']['table'] . " database";
                    } else {
                        $actionsTaken[] = "There was a problem adding the file " . $action['sourceFilename'] . " to the " . $action['sql']['table'] . " database and the filename was not moved";
                    }
                }
            }
            return Response::json($actionsTaken);
        });
    });
//});

//Could be impoved by checking if the parent directory is writable
function makedirsCustom($dirpath, $mode=0777)
{
    return is_dir($dirpath) || mkdir($dirpath, $mode, true);
}
