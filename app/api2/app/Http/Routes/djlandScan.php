<?php

use Symfony\Component\Process\Process as Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

Route::group(['middleware' => 'auth'], function () {
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
