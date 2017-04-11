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

        if( Input::get('format_id') == $old_entry->format_id
          && Input::get('catalog') == $old_entry->catalog
          && Input::get('cancon') == $old_entry->cancon
          && Input::get('femcon') == $old_entry->femcon
          && Input::get('local') == $old_entry->local
          && Input::get('playlist') == $old_entry->playlist
          && Input::get('compilation') == $old_entry->compilation
          && Input::get('digitized') == $old_entry->digitized
          && Input::get('status') == $old_entry->status
          && Input::get('artist') == $old_entry->artist
          && Input::get('title') == $old_entry->title
          && Input::get('label') == $old_entry->label
          && Input::get('genre') == $old_entry->genre) {
            return "No changes made";
          }

        $newedits = Edits::create([
            'format_id' => Input::get('format_id'),
            'old_format_id' => $old_entry->format_id,
            'catalog' => Input::get('catalog'),
            'old_catalog' => $old_entry->catalog,
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
            'status' => Input::get('status'),
            'old_status' => $old_entry->status,
            'artist' => Input::get('artist'),
            'old_artist' => $old_entry->artist,
            'title' => Input::get('title'),
            'old_title' => $old_entry->title,
            'label' => Input::get('label'),
            'old_label' => $old_entry->label,
            'genre' => Input::get('genre'),
            'old_genre' => $old_entry->genre,
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

        if(Input::get('title') != null) {
            $entry -> title = Input::get('title');
        }
        if(Input::get('artist') != null) {
            $entry -> artist = Input::get('artist');
        }
        if(Input::get('label') != null) {
            $entry -> label = Input::get('label');
        }
        if(Input::get('genre') != null) {
            $entry -> genre = Input::get('genre');
        }
        if(Input::get('catalog') != null) {
            $entry -> catalog = Input::get('catalog');
        }
        if(Input::get('format_id') != null) {
            $entry -> format_id = Input::get('format_id');
        }
        if(Input::get('status') != null) {
            $entry -> status = Input::get('status');
        }
        if(Input::get('cancon') != null) {
            $entry -> cancon = Input::get('cancon');
        }
        if(Input::get('femcon') != null) {
            $entry -> femcon = Input::get('femcon');
        }
        if(Input::get('playlist') != null) {
            $entry -> playlist = Input::get('playlist');
        }
        if(Input::get('local') != null) {
            $entry -> local = Input::get('local');
        }
        if(Input::get('compilation') != null) {
            $entry -> compilation = Input::get('compilation');
        }
        if(Input::get('digitized') != null) {
            $entry -> digitized = Input::get('digitized');
        }

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
