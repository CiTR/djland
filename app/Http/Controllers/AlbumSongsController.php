<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Song;
use App\Album;

class AlbumSongsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  int  $album_id
     * @return \Illuminate\Http\Response
     */
    public function index($album_id)
    {
        $songs = Song::where('album_id', '=', $album_id)->get();

        return response()->json($songs);
    }

    /**
     * Show the form for creating a new resource.
     * 
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return app(SongController::class)->create();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $album_id
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $album_id)
    {
        $new_request = $request->merge(['album_id' => $album_id]);

        return app(SongController::class)->store($new_request);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $album_id
     * @param  int  $song_id
     * @return \Illuminate\Http\Response
     */
    public function show($album_id, $song_id)
    {
        return app(SongController::class)->show($song_id);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $album_id
     * @param  int  $song_id
     * @return \Illuminate\Http\Response
     */
    public function edit($album_id, $song_id)
    {
        return app(SongController::class)->edit($song_id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $album_id
     * @param  int  $song_id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $album_id, $song_id)
    {
        $new_request = $request->merge(['album_id' => $album_id]);

        return app(SongController::class)->update($new_request, $song_id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $album_id
     * @param  int  $song_id
     * @return \Illuminate\Http\Response
     */
    public function destroy($album_id, $song_id)
    {
        return app(SongController::class)->destroy($song_id);
    }
}
