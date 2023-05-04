<?php

use App\Genre as Genre;
use App\Subgenre as Subgenre;
use Validator as Validator;

Route::group(['middleware' => 'auth'], function(){
    Route::group(['prefix'=>'genres'], function(){
        //Get list of genres
        Route::get('/', function(){
            $result = Genre::all();
            if(!$result->isEmpty()) return Response::json($result);
            else return Response::json();
        });
        //Get a list of subgenres for a genre given the genre id
        Route::get('/subgenres/{id}', function($id){
            $rules = array(
                'id' => 'required|integer|min:1'
            );
            $data = array('id' => $id);
            $validator = Validator::make($data, $rules);
            if($validator->fails()) return response($validator->errors()->all(),422);
            else {
                try{
                    $subgenres = Subgenre::where('parent_genre_id', '=', $id)->get();
                    if($subgenres!=null) return $subgenres;
                    else return Response::json();
                } catch(Exception $e){
                    return $e->getMessage();
                }
            }
        });
        /** Use staff middleware for POST, PUT and DELETE routes so that
          * only staff can update the genre listings even if they
          * figured out how to API
          */
        Route::group(['middleware' => 'staff'], function(){
            //Create a genre
            Route::post('/', function(){
                try{
                    $messages = array('genre.unique' => 'There is already this genre in the system! Please choose a unique genre name.');
                    $rules = array(
                        'genre' => 'required|unique:genres|regex:/^[\pL\-\_\/\\\~\!\@\#\$\&\*\ ]+$/u',
                        'default_crtc_category' => 'required|integer|in:10,20,30,40,50'
                    );
                    $validator = Validator::make(Input::all(), $rules, $messages);
                    if($validator->fails()) return response($validator->errors()->all(),422);
                    else{
                            $genre = Genre::create([
                                'genre' => Input::get('genre'),
                                'default_crtc_category' => Input::get('default_crtc_category'),
                                'created_by' => $_SESSION['sv_id'],
                                'updated_by' => $_SESSION['sv_id']
                            ]);
                            return Response::json($genre);
                    }
                } catch(Exception $e){
                    return $e->getMessage();
                }
            });
            //Update a genre given it's id
            Route::put('/', function(){
                $messages = array('genre.unique' => 'There is already this genre in the system! Please choose a unique genre name.');
                if(Input::get('genre') == Genre::find(Input::get('id'))['genre']){
                    $rules = array(
                        'id' => 'required|integer|min:1',
                        'genre' => 'required|regex:/^[\pL\-\_\/\\\~\!\@\#\$\&\*\ ]+$/u',
                        'default_crtc_category' => 'required|integer|in:10,20,30,40,50'
                    );
                } else {
                    $rules = array(
                        'id' => 'required|integer|min:1',
                        'genre' => 'required|unique:genres|regex:/^[\pL\-\_\/\\\~\!\@\#\$\&\*\ ]+$/u',
                        'default_crtc_category' => 'required|integer|in:10,20,30,40,50'
                    );
                }
                $validator = Validator::make(Input::all(), $rules, $messages);
                if($validator->fails()) return response($validator->errors()->all(),422);
                else {
                    try{
                        $genre = Genre::find(Input::get('id'));
                        $prev_genre = $genre->genre;
                        $genre->genre = Input::get('genre');
                        $genre->default_crtc_category = Input::get('default_crtc_category');
                        $genre->updated_by = $_SESSION['sv_id'];
                        $genre->save();
                        return Response::json("Update genre " . $prev_genre . " to " . Input::get('genre'));
                    } catch(Exception $e){
                        return $e->getMessage();
                    }
                }
            });
            //Delete a genre given it's id
            Route::delete('/', function(){
                $rules = array(
                    'id' => 'required|integer|min:1'
                );
                $validator = Validator::make(Input::all(), $rules);
                if($validator->fails()) return response($validator->errors()->all(),422);
                else {
                    try{
                        $genre = Genre::find(Input::get('id'));
                        Genre::destroy(Input::get('id'));
                        return Response::json('The Genre \'' . $genre->genre .'\' has been successfully deleted.');
                    } catch(Exception $e){
                        return $e->getMessage();
                    }
                }
            });
        });
    });

    Route::group(['prefix'=>'subgenres'], function(){
        //Get list of subgenres
        Route::get('/', function(){
            if(Input::get('id') != null) $result=Subgenre::find(Input::get('id')->get(0));
            else $result = Subgenre::all();
            if(!$result->isEmpty()) return Response::json($result);
            else return Response::json();
        });
        //Get a subgenre given it's ID
        Route::get('/{id}', function($id){
            $result = Subgenre::find($id);
            if($result!=null) return Response::json($result);
            else return Response::json();
        });
        //Get a subgenre's parentgenre given the subgenre's id
        // (returns the name of the genre and not the id)
        Route::get('/{id}/parentgenre', function($id=id){
            $result = Subgenre::find($id);
            $parent = Genre::find($result->parent_genre_id)->genre;
            if(!$result->isEmpty() && $parent!=null) return Response::json($parent);
            else return Response::json();
        });
        //Create a subgenre
        Route::post('/', function(){
            $messages = array('subgenre.unique' => 'There is already this subgenre in the system! Please choose a unique genre name.');
            $rules = array(
                'parent_genre_id' => 'required|integer|min:1',
                'subgenre' => 'required|unique:subgenres|regex:/^[\pL\-\_\/\\\~\!\@\#\$\&\*\ ]+$/u'
            );
            $validator = Validator::make(Input::all(), $rules, $messages);
            if($validator->fails()) return response($validator->errors()->all(),422);
            else{
                try{
                    $subgenre = Subgenre::create([
                        'subgenre' => Input::get('subgenre'),
                        'parent_genre_id' => Input::get('parent_genre_id'),
                        'created_by' => $_SESSION['sv_id'],
                        'updated_by' => $_SESSION['sv_id']
                    ]);
                    return Response::json($subgenre);
                } catch(Exception $e){
                    return $e->getMessage();
                }
            }
        });
        //Update a subgenre given an id
        Route::put('/', function(){
            $messages = array('subgenre.unique' => 'There is already this subgenre in the system! Please choose a unique genre name.');
            if(Input::get('subgenre') == Subgenre::find(Input::get('id'))['subgenre']){
                $rules = array(
                    'id' => 'required|integer',
                    'subgenre' => 'required|regex:/^[\pL\-\_\/\\\~\!\@\#\$\&\*\ ]+$/u'
                );
            } else {
                $rules = array(
                    'id' => 'required|integer',
                    'subgenre' => 'required|unique:subgenres|regex:/^[\pL\-\_\/\\\~\!\@\#\$\&\*\ ]+$/u'
                );
            }
            $validator = Validator::make(Input::all(), $rules, $messages);
            if($validator->fails()) return response($validator->errors()->all(),422);
            else {
                try{
                    $subgenre = Subgenre::find(Input::get('id'));
                    $prev_subgenre = $subgenre->subgenre;
                    $subgenre->subgenre = Input::get('subgenre');
                    $subgenre->updated_by = $_SESSION['sv_id'];
                    $subgenre->save();
                    return Response::json("Update subgenre " . $prev_subgenre . " to " . Input::get('subgenre'));
                } catch(Exception $e){
                    return $e->getMessage();
                }
            }
        });
        //Delete a subgenre given an id
        Route::delete('/', function(){
            $rules = array(
                'id' => 'required|integer'
            );
            $validator = Validator::make(['id' => Input::get('id')], $rules);
            if($validator->fails()) return response($validator->errors()->all(),422);
            else {
                try{
                    $subgenre = Subgenre::find(Input::get('id'));
                    Subgenre::destroy(Input::get('id'));
                    return Response::json('The Subgenre \'' . $subgenre->subgenre .'\' has been successfully deleted.');
                } catch(Exception $e){
                    return $e->getMessage();
                }
            }
        });
    });
});
