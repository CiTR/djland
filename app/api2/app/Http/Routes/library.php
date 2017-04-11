<?php

use App\Library_Edit as Edits;
use App\Library as Library;
use App\Member as Member;
use Carbon\Carbon;
use Validator as Validator;

//Post to this route to write to the library edits table
Route::group(['middleware' => 'auth'], function(){
    Route::group(array('prefix'=>'library'), function(){
        Route::get('/',function(){
            //only return ids because the table is too big
            try{
                return Library::select('id')->get();
            } catch(Exception $e){
                return $e->getMessage();
            }
        });
        Route::get('/{id}',function($id=id){
            $result = Library::find($id);
            $result['songs'] = Library::find($id)->songs;
            return $result;
        });
        Route::post('/',function(){
            return Library::create([
                'title' => Input::get('title')
            ]);
        });
    });
});
