<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Kris\LaravelFormBuilder\FormBuilder;
use App\Forms\AlbumForm;
use App\Forms\SongForm;

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
     * @todo  Implement ability to add songs directly
     * 
     * @return \Illuminate\Http\Response
     */
    public function create(FormBuilder $formBuilder)
    {
        $form = $formBuilder->create(AlbumForm::class, [
            'method' => 'POST',
            'url' => route('albums.store'),
        ]);

        $form->addBefore('submit', 'songs', 'collection', [
            'type' => 'form',
            'property' => 'id',
            'options' => [
                'label' => false,
                'class' => $formBuilder->create(SongForm::class),
            ],
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

        // Get array of all the fillable fields
        $fillable = app(Album::class)->getFillable();

        $album = Album::firstOrNew($request->only($fillable));

        // Bool was the album saved
        $saved = $album->save();

        // New instance for song models
        $songs = collect([]);

        if ($request->has('songs')) {
            foreach ($request->input('songs') as $song) {
                // Do what the Song model's observers and mutators normally would
                if (preg_match('/^([0-9]*)[:]([0-9]*)$/', $song['length'], $matches)) {
                    $song['length'] = $matches[1]*60+$matches[2];
                }
                if (empty($song['artist'])) {
                    $song['artist']  = $album->artist;
                }

                // Push the new or fetched song object into the collection
                $songs->push($album->songs()->firstOrCreate($song));
            }
        }

        // Count how many songs were recently created
        $new_songs = $songs->reject(function ($song, $key) {
            return !$song->wasRecentlyCreated;
        })->count();

        // Fetch the album's songs because I wanna see that in the JSON
        $album->load('songs');

        if (!$album->wasRecentlyCreated && !$new_songs) {
            return response()->json($album, 409);
        }

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
        $album = Album::with('songs')->findOrFail($id);

        $form = $formBuilder->create(AlbumForm::class, [
            'method' => 'PUT',
            'url' => route('albums.update', ['id' => $id]),
            'model' => $album,
        ]);

        // @see https://github.com/kristijanhusak/laravel-form-builder/issues/162#issuecomment-144645617
        $songForm = $formBuilder->create(SongForm::class, [], ['includeHiddenId' => true]);

        $form->addBefore('submit', 'songs', 'collection', [
            'type' => 'form',
            'options' => [
                'label' => false,
                'class' => $songForm,
            ],
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
