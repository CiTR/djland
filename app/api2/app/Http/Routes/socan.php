<?php

use App\Socan as Socan;
use Validator\Validator;

Route::group(array('prefix'=>'socan'), function () {
    Route::get('/', function () {
        return Socan::all();
    });
    Route::put('/', function () {
        $rules = array(
            'socanStart' => 'required',
            'socanEnd' => 'required'
        );
        $messages = array(
            'socanStart.required' => 'Please enter a start date',
            'socanEnd.required' => 'Please enter an end date'
        );
        $validator = Validator::make(Input::get(), $rules, $messages);
        if (!($validator->fails())) {
            try {
                return Socan::create((array) Input::get());
            } catch (PDOException $e) {
                return $e->getMessage();
            }
        } else {
            return response($validator->errors()->all(), 422);
        }
    });
    Route::group(array('prefix'=>'{id}'), function ($id = id) {
        Route::post('/', function ($id) {
            $rules = array(
                'socanStart' => 'required',
                'socanEnd' => 'required'
            );
            $messages = array(
                'socanStart.required' => 'Please enter a start date',
                'socanEnd.required' => 'Please enter an end date'
            );
            $validator = Validator::make($rules, Input::get(), $messages);
            if (!($validator->fails())) {
                try {
                    return Response::json(Socan::find($id)->update((array) Input::get()));
                } catch (PDOException $e) {
                    return $e->getMessage();
                }
            } else {
                return response($validator->errors()->all(), 422);
            }
        });
        Route::delete('/', function ($id) {
            return Response::json(Socan::find($id)->delete());
        });
    });
    //Check to see if the time requested is inside a socan period.
    Route::group(array('prefix'=>'check'), function () {
        Route::get('/{time}', function ($unixtime = time) {
            $now = $unixtime;
            $socan = Socan::all();
            foreach ($socan as $period) {
                if (strtotime($period['socanStart']) <= $now && strtotime($period['socanEnd']) >= $now) {
                    return Response::json(true);
                }
            }
            return Response::json(false);
        });
        Route::get('/', function () {
            $now = strtotime('now');
            $socan = Socan::all();
            foreach ($socan as $period) {
                if (strtotime($period['socanStart']) <= $now && strtotime($period['socanEnd']) >= $now) {
                    return Response::json(true);
                }
            }
            return Response::json(false);
        });
    });
});
