<?php

use App\Library_Edit as Edits;
use App\Library as Library;
use App\Member as Member;
use Carbon\Carbon;
use Validator as Validator;

//Post to this route to write to the library edits table
Route::post('/libraryedits', function(){

    try{
        // get the old values that are currently in the library
        $old_entry = Library::find(Input::get('libraryID'));

        $newedits = Edits::create([
            'format_id' => Input::get('format_id'),
            'old_format_id' => $old_entry->format_id,
            'catalog' => Input::get('catalog'),
            'old_catalog' => $old_entry->catalog,
            'crtc' => null,
            'old_crtc' => null,
            'cancon' => Input::get('cancon'),
            'old_cancon' => $old_entry->cancon,
            'femcon' => Input::get('femcon'),
            'old_femcon' => $old_entry->femcon,
            'local' => Input::get('local'),
            'old_local' => $old_entry->local,
            'playlist' => Input::get('playlist'),
            'old_playlist' => $old_entry->playlist,
            'compilation' => Input::get('compilation'),
            'old_compilation' => $old_entry->compilation,
            'digitized' => Input::get('digitized'),
            'old_digitized' => $old_entry->digitized,
            'status' => null,
            'old_status' => null,
            'artist' => Input::get('artist'),
            'old_artist' => $old_entry->artist,
            'title' => Input::get('title'),
            'old_title' => $old_entry->title,
            'label' => Input::get('label'),
            'old_label' => $old_entry->label,
            'genre' => Input::get('genre'),
            'old_genre' => $old_entry->genre,
            'description' => null,
            'old_description' => null,
            'email' => null,
            'old_email' => null,
            'library_id' => Input::get('libraryID')
        ]);
        return $newedits;
    } catch(Exception $e){
        return $e->getMessage();
    }
});

//Post to this route to update a library entry
Route::put('/updateentry', function(){
    try{
        $entry = Library::find(Input::get('libraryID'));

        $entry -> title   = Input::get('title');
        $entry -> artist  = Input::get('artist');
        $entry -> label   = Input::get('label');
        $entry -> genre   = Input::get('genre');
        $entry -> catalog = Input::get('catalog');
        $entry -> format_id  = Input::get('format_id');
        $entry -> cancon  = Input::get('cancon');
        $entry -> femcon  = Input::get('femcon');
        $entry -> playlist = Input::get('playlist');
        $entry -> local   = Input::get('local');
        $entry -> compilation = Input::get('compilation');
        $entry -> digitized = Input::get('digitized');

        $entry ->save();

        return Response::json("Updated library entry #" . $entry -> id);
    } catch (Exception $e){
        return $e->getMessage();
    }
});
// Get recent entries in the library edits table
Route::get('/recentedits', function(){
    $result = Edits::orderBy('id', 'desc')->take(30)->get();
    if(!$result->isEmpty()) return Response::json( $result );
    else return Response::json();
});
// Get an entry in the library edits table
Route::get('/recenteditentry', function(){
    $result = Edits::find(Input::get('id'));
    if(!$result->isEmpty()) return Response::json( $result );
    else return Response::json();
});
