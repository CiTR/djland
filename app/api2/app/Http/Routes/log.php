<?php
//Routes in this file inplement DB-based logger routes for DJLand

use App\Log as Log;
use App\Member as Member;

//All routes in this group require that we be logged in to use
Route::group(array('middleware' => 'auth'), function () {
  Route::group(array('prefix' => 'log'), function () {
    //Most recent first with optional limit/offset (optional limit and offset passed in query string);
    Route::get('/', function () {
      try {
        //Support limit/offset if we're looking for a certain amount
        if (Input::get('limit')) {
          $limit = Input::get('limit');
          if (!is_numeric($limit)) {
            $input = "Input was: \"" . $limit . "\", offset was: \"" . Input::get('offset') . "\"";
            Log::write('Non-numerical limit passed to /api2/public/log/?limit=&offset=', $input);
            return "API error, check DJLand API Log";
          }
        } else {
          $limit = 500;
        }
        if (Input::get('offset')) {
          $offset = Input::get('offset');
          if (!is_numeric($offset)) {
            $input = "Input was: \"" . $limit . "\", offset was: \"" . $offset . "\"";
            Log::write('Non-numerical offset passed to /api2/public/log/?limit=&offset=', $input);
            return "API error, check DJLand API Log";
          }
        } else {
          $offset = 0;
        }
        return Response::json(Log::orderBy('created_at', 'desc')->offset($offset)->limit($limit)->get());
      } catch (Exception $e) {
        return $e->getMessage();
      }
    });
    //Most recent first with limit/offset
    Route::get('/bylimitoffset/{limit}/{offset}/', function ($limit = limit, $offset = offset) {
      $input = 'Input was: \"' . $limit . '\", offset was: \"' . $offset . "\"";
      if (is_numeric($limit) && is_numeric($offset)) {
        return Response::json(Log::all()->orderBy('created_at', 'desc')->offset($offset)->limit($limit)->get());
      } else {
        Log::write('Non-numerical limit or offset passed to /api2/public/log/bylimitoffset/{limit}/{offset}', $input);
        return "API error, check DJLand API Log";
      }
    });
    //dates are to be in 'yyyy-mm-dd hh:mm:ss' format (24-hour)
    //Optional limit/offset via query string
    Route::get('/bydatebefore/{date}', function ($date = date) {
      $_date = (new Datetime($date))->format('Y-m-d H:i:s');
      if (Input::get('limit')) {
        $limit = Input::get('limit');
        if (!is_numeric($limit)) {
          $input = "Input was: \"" . $limit . "\", offset was: \"" . Input::get('offset') . "\"";
          Log::write('Non-numerical limit passed to /api2/public/log/bydatebefore/{date}/?limit=&offset=', $input);
          return "API error, check DJLand API Log";
        }
      } else {
        $limit = 500;
      }
      if (Input::get('offset')) {
        $offset = Input::get('offset');
        if (!is_numeric($offset)) {
          $input = "Input was: \"" . $limit . "\", offset was: \"" . $offset . "\"";
          Log::write('Non-numerical offset passed to /api2/public/log/bydatebefore/{date}/?limit=&offset=', $input);
          return "API error, check DJLand API Log";
        }
      } else {
        $offset = 0;
      }
      return Response::json(Log::orderBy('created_at', 'desc')->where('DATE_CREATED', '<', date($_date))->offset($offset)->limit($limit)->get());
    });
    //dates are to be in 'yyyy-mm-dd hh:mm:ss' format (24-hour)
    //Optional limit/offset via query string
    Route::get('/bydateafter/{date}', function ($date = date) {
      $_date = (new Datetime($date))->format('Y-m-d H:i:s');
      if (Input::get('limit')) {
        $limit = Input::get('limit');
        if (!is_numeric($limit)) {
          $input = "Input was: \"" . $limit . "\", offset was: \"" . Input::get('offset') . "\"";
          Log::write('Non-numerical limit passed to /api2/public/log/bydateafter/{date}/?limit=&offset=', $input);
          return "API error, check DJLand API Log";
        }
      } else {
        $limit = 500;
      }
      if (Input::get('offset')) {
        $offset = Input::get('offset');
        if (!is_numeric($offset)) {
          $input = "Input was: \"" . $limit . "\", offset was: \"" . $offset . "\"";
          Log::write('Non-numerical offset passed to /api2/public/log/bydateafter/{date}/?limit=&offset=', $input);
          return "API error, check DJLand API Log";
        }
      } else {
        $offset = 0;
      }
      return Response::json(Log::orderBy('created_at', 'desc')->where('DATE_CREATED', '>', date($_date))->offset($offset)->limit($limit)->get());
    });
    //Start and end dates are to be in 'yyyy-mm-dd hh:mm:ss' format (24-hour)
    //Date ranges are start and end inclusive
    //Optional limit/offset via query string
    Route::get('/bydaterange/{start}/{end}/', function ($start = start, $end = end) {
      $_start = (new Datetime($start))->format('Y-m-d H:i:s');
      $_end = (new Datetime($end))->format('Y-m-d H:i:s');
      if (Input::get('limit')) {
        $limit = Input::get('limit');
        if (!is_numeric($limit)) {
          $input = "Input was: \"" . $limit . "\", offset was: \"" . Input::get('offset') . "\"";
          Log::write('Non-numerical limit passed to /api2/public/log/bydaterange/{start}/{end}/?limit=&offset=', $input);
          return "API error, check DJLand API Log";
        }
      } else {
        $limit = 500;
      }
      if (Input::get('offset')) {
        $offset = Input::get('offset');
        if (!is_numeric($offset)) {
          $input = "Input was: \"" . $limit . "\", offset was: \"" . $offset . "\"";
          Log::write('Non-numerical offset passed to /api2/public/log/bydaterange/{start}/{end}/?limit=&offset=', $input);
          return "API error, check DJLand API Log";
        }
      } else {
        $offset = 0;
      }
      return Response::json(Log::orderBy('created_at', 'desc')->where('DATE_CREATED', '>=', date($_start))->where('DATE_CREATED', '<=', date($_end))
        ->offset($offset)->limit($limit)->get());
    });
    Route::get('byid/{id}', function ($id = id) {
      if (is_numeric($id)) {
        return Response::json(Log::where('index', '=', $id)->get());
      } else {
        Log::write('Non-numerical id passed to /api2/public/log/byid/{id}', "The id entered was: " . $id);
        return "API error, check DJLand API Log";
      }
    });
    Route::get('/byuser/{user}', function ($user = user) {
      return Response::json(Log::where('user', '=', $user)->get());
    });
    /* Not used - probably never a use case where an external body would want to write to djland's log
           Otherwise we just use Log::write from within the API
        Route::post('/',function(){
        });
        Route::delete('/{id}',function(){
        });
        Route::put('/{id}',function(){
        });
        */
  });
});
