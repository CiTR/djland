<?php

namespace App\Http\Controllers;

use App\Song;
use Illuminate\Http\Request;

class SongController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $songs = Song::all();

        return response()->json($songs);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @todo  Create form for new resources
     * 
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return response("Coming Soon", 404);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $song = Song::firstOrNew($request->all());

        if ($song->exists) {
            return response()->json($song, 409);
        }

        $saved = $song->save();

        if ($saved) {
            return response()->json($song, 201);
        }

        return response('Song not created', 500);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $song = Song::findOrFail($id);

        return response()->json($song);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @todo   return edit form filled out
     * 
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $song = Song::findOrFail($id);

        return response()->json($song);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $song = Song::findOrFail($id);

        $song->fill($request->all());

        if ($song->isDirty()) {
            if ($song->save()) {
                return response('Song updated');
            }

            return response('Error updating song', 500);
        }

        return response('Song not updated. No changes found');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $success = Song::destroy($id);

        if ($success) {
            return response('Song deleted');
        }
        
        return response('Song not deleted', 500);
    }
}
