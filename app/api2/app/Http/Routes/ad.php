<?php

use App\Ad as Ad;

Route::get('/ads/{unixtime}-{duration}/{show_id}', function ($unixtime = unixtime, $duration = duration, $show_id = show_id) {
  $ads = Ad::where('time_block', '=', $unixtime)->orderBy('num', 'asc')->get();
  if (sizeof($ads) > 0) return Response::json($ads);
  else return Ad::generateAds($unixtime, $duration, $show_id);
});
