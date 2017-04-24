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

            foreach ($actions as $action) {
                //Step one: add to sql table
                $sqlStatus = false;
                try {
                    if ($action['table'] == "submissions") {
                        $sqlId = SubmissionsSongs::create([
                            'submission_id' => $action['vars'][0],
                            'artist' => $action['vars'][1],
                            'album_artist' => $action['vars'][2],
                            'album_title' => $action['vars'][3],
                            'song_title' => $action['vars'][4],
                            'track_num' => $action['vars'][5],
                            'genre' => $action['vars'][6],
                            'compilation' => $action['vars'][7],
                            'crtc' => $action['vars'][8],
                            'year' => $action['vars'][9],
                            'length' => $action['vars'][10],
                            'file_location' => $action['vars'][11]
                        ]);
                        $sqlStatus = true;
                    } elseif ($action['table'] == "library") {
                        $sqlId = LibrarySongs::create([
                            'submission_id' => $action['vars'][0],
                            'artist' => $action['vars'][1],
                            'album_artist' => $action['vars'][2],
                            'album_title' => $action['vars'][3],
                            'song_title' => $action['vars'][4],
                            'track_num' => $action['vars'][5],
                            'genre' => $action['vars'][6],
                            'compilation' => $action['vars'][7],
                            'crtc' => $action['vars'][8],
                            'year' => $action['vars'][9],
                            'length' => $action['vars'][10],
                            'file_location' => $action['vars'][11]
                        ]);
                        $sqlStatus = true;
                    } elseif ($action['table'] == 'manual') {
                        //Don't create sql entries
                        $sqlStatus = true;
                    }
                } catch (Exception $e) {
                    $sqlStatus = false;
                }

                $fileStatus = false;
                if ($sqlStatus) {
                    //Step two: move file if a sql entry is made
                    $fileStatus = rename($action['sourceFilename'], $action['destFilename']);
                }

                if ($fileStatus && $sqlStatus) {
                    $actionsTaken[] = "The file " . $action['sourceFilename'] . " was moved to " . $action['destFilename'] . " and successfully added to the " . $action['table'] . " database";
                } elseif (!$fileStatus && $sqlStatus) {
                    $actionsTaken[] = "There was a problem moving the file from " . $action['sourceFilename'] . " to " . $action['destFilename'] . " but was successfully added to the " . $action['table'] . " database";
                } else {
                    $actionsTaken[] = "There was a problem adding the file " . $action['sourceFilename'] . " to the " . $action['table'] . " database and the filename was not moved";
                }
            }
            return Response::json($actionsTaken);
        });
    });
//});
