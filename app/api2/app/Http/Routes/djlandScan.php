<?php

use Symfony\Component\Process\Process as Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

Route::group(['middleware' => 'auth'], function () {
    Route::group('/djlandscan', function (){
        //Get a list of potential scan actions
        Route::get('/generatescanresults', function () {
            $process = new Process("/usr/bin/python3 djlandScan.py");
            $process->run();

            // executes after the command finishes
            if (!$process->isSuccessful()) {
                throw new ProcessFailedException($process);
            }
            return($process->getOutput());
        });
        Route::post('/doimport',function () {
            $actions = Input::get('actions');
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
        });
    });
});
