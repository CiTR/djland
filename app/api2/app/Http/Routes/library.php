<?php

use App\Library as Library;
use App\TypesFormat as TypesFormat;

//Post to this route to write to the library edits table
Route::group(['middleware' => 'auth'], function () {
    Route::group(array('prefix'=>'library'), function () {
        Route::get('/', function () {
            //only return ids because the table is too big
            try {
                return Library::select('id')->get();
            } catch (Exception $e) {
                return $e->getMessage();
            }
        });
        Route::get('/{id}', function ($id=id) {
            if ($id == 'formats') {
                return Response::json(TypesFormat::select('id', 'name')->get());
            } else {
                $result = Library::find($id);
                $result['songs'] = Library::find($id)->songs;
                return $result;
            }
        });
        Route::post('/', function () {
            if (!is_numeric(Input::get('format'))) {
                switch (Input::get('format')) {
                    case "7i":
                        $format = 3;
                        break;
                    case "7\"":
                        $format = 3;
                        break;
                    case "??":
                        $format = 8;
                        break;
                    default:
                        $format = TypesFormat::select('id')->where('name', 'like', strtoupper(Input::get('format')))->get();
            }
            } else {
                $format = Input::get('format');
            }

            if (Input::get('playlist') == 1) {
                $status = 'P';
            } else {
                $status = 'A';
            }

            return Library::create([
                'catalog' => Input::get('catalog'),
                'format_id' => $format,
                'status' => $status,
                'artist' => Input::get('artist'),
                'title' => Input::get('album_title'),
                'label' => Input::get('label'),
                'genre' => Input::get('genre'),
                'cancon' => Input::get('cancon'),
                'femcon' => Input::get('femcon'),
                'local' => Input::get('local'),
                'compilation' => Input::get('compilation'),
                'digitized' => Input::get('in_sam')
            ]);
        });
    });
});
