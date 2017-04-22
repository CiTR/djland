<?php

use Symfony\Component\Process\Process as Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

Route::group(['middleware' => 'auth'], function () {
    Route::group(['prefix' =>'/djlandscan'], function (){
        //Get a list of potential scan actions
        Route::get('/generatescanresults', function () {
            //$process = new Process("/usr/bin/python3 djlandScan.py");
            //$process->run();

            // executes after the command finishes
            //if (!$process->isSuccessful()) {
                //throw new ProcessFailedException($process);
            //}
            //return($process->getOutput());

           $testret = array(
               array(
                   'source' => "/home/filenameone",
                   'artist' => "test artist one",
                   'album' => "tests album one",
                   'song' => "test song one",
                   'genre' => "Rock",
                   'year' => '1994',
                   'matchedString' => 'Library #12345',
                   'actionsList' => array('Move to submissions', 'Move to Library', 'Move to manual processing folder')
               ),
               array(
                   'source' => "/home/filenametwo",
                   'artist' => "test artist two",
                   'album' => "tests album two",
                   'song' => "test song two",
                   'genre' => "Rock",
                   'year' => '1994',
                   'matchedString' => 'Library #1234556',
                   'actionsList' => array('Move to submissions', 'Move to Library', 'Move to manual processing folder')
               ),
               array(
                   'source' => "/home/filename",
                   'artist' => "test artist three",
                   'album' => "tests album three",
                   'song' => "test song three",
                   'genre' => "Rock",
                   'year' => '1994',
                   'matchedString' => 'Library #12345',
                   'actionsList' => array('Move to submissions', 'Move to Library', 'Move to manual processing folder')
               ),
           );
           return Response::json($testret);
        });
        Route::post('/doimport',function () {
            /*$actions = Input::get('actions');
            //Keep track of what happened in order to return a report to the user
            $actionsTaken = array();
            //Using a python script to do the moving, could do this just with php
            foreach($actions as $key => $action){
                $process = new Process("/usr/bin/python3 djlandScan.py --action" + $action);
                //could run this async but don't want to spawn a thousand processes
                $process->run();
                //If we want to do async, use this:
                //$process->start();

                //Executes after the command finishes
                if (!$process->isSuccessful()) {
                    throw new ProcessFailedException($process);
                }
                //Push result onto the array of actions taken
                $actionsTaken[] = $process->getOutput();
            }
            //Return report of things that happened
            return $actionsTaken;
            */
            $testret = array(
                array(
                    'source' => "/home/filenameone",
                    'action' => "Move to library",
                    'newID' => "Catalog  # 1231234",
                    'destination' => "L:/somewhere",
                ),
                array(
                    'source' => "/home/filenametwo",
                    'action' => "Move to library",
                    'newID' => "Catalog  # 123122",
                    'destination' => "L:/somewhere",
                ),
                array(
                    'source' => "/home/filenamethree",
                    'action' => "Move to library",
                    'newID' => "Catalog  # 123",
                    'destination' => "L:/somewhere",
                ),
            );
            return Response::json($testret);
        });
    });
});
