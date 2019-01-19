<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::resource('members', 'UserController');

Route::resource('albums', 'AlbumController');

Route::resource('songs', 'SongController');

Route::resource('shows', 'ShowController');

Route::resource('episodes', 'EpisodeController');

Route::resource('albums/{album_id}/songs', 'AlbumSongsController');

Route::model('ad_scheduler', App\AdSchedule::class);

Route::resource('ad-scheduler', 'AdScheduleController');

Route::get('/ad-scheduler/get-from-datetime-range', function (Illuminate\Http\Request $request) {
    $start = $request->input('start');
    $end = $request->input('end');
    $schedules = App\AdSchedule::inDateTimeRange($start, $end)->get();

    $ads = $schedules->reduce(function ($carry, $sched) use ($start, $end) {
        return $carry->concat($sched->getAdsForRange($start, $end));
    }, collect([]));

    $ads = $ads->sortBy('time')->values();

    return response()->json($ads);
});
