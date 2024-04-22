<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return response()->json(['status' => 'running']);
});
/*
Route::get('/php-info', function () {
    return response()->json(phpinfo());
});
*/