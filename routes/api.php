<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/sessions', function () {
    return \App\Http\Resources\Session::collection(\App\Session::all());
});

Route::get('/sessions/combobox', function () {
    return \App\Http\Resources\Session::collection(\App\Session::all());
});
