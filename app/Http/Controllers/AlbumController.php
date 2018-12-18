<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Album;

class AlbumController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $albums = Album::all();

        return response()->json($albums);
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
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $album = Album::firstOrNew($request->all());

        if ($album->exists) {
            return response()->json($album, 409);
        }

        $saved = $album->save();

        if ($saved) {
            return response()->json($album, 201);
        }

        return response('Album not created', 500);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $album = Album::with('songs')->findOrFail($id);

        return response()->json($album);
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
        $album = Album::findOrFail($id);

        return response()->json($album);
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
        $album = Album::findOrFail($id);

        $album->fill($request->all());

        if ($album->isDirty()) {
            if ($album->save()) {
                return response('Album updated');
            }

            return response('Error updating album', 500);
        }

        return response('Album not updated. No changes found');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $success = Album::destroy($id);

        if ($success) {
            return response('Album deleted');
        }
        
        return response('Album not deleted', 500);
    }
}
