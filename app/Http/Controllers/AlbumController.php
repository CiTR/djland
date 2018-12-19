<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Kris\LaravelFormBuilder\FormBuilder;
use App\Forms\AlbumForm;

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
    public function create(FormBuilder $formBuilder)
    {
        $form = $formBuilder->create(AlbumForm::class, [
            'method' => 'POST',
            'url' => route('albums.store'),
        ]);

        return view('forms.basic', compact('form'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, FormBuilder $formBuilder)
    {
        $form = $formBuilder->create(AlbumForm::class);

        if (!$form->isValid()) {
            return redirect()->back()->withErrors($form->getErrors())->withInput();
        }

        $album = Album::firstOrNew($request->except('_token'));

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
    public function edit($id, FormBuilder $formBuilder)
    {
        $album = Album::findOrFail($id);

        $form = $formBuilder->create(AlbumForm::class, [
            'method' => 'PUT',
            'url' => route('albums.update', ['id' => $id]),
            'model' => $album,
        ]);

        return view('forms.basic', compact('form'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id, FormBuilder $formBuilder)
    {
        $form = $formBuilder->create(AlbumForm::class);

        if (!$form->isValid()) {
            return redirect()->back()->withErrors($form->getErrors())->withInput();
        }

        $album = Album::findOrFail($id);

        $album->fill($request->except('_token'));

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
