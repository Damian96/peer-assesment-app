<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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

/**
 * auth:api Middleware -> expects always JSON !!
 */

Route::post('/user/login', 'ApiController@login')->name('api.login');
Route::middleware('api')->get('/user', function (Request $request) {
    return json_encode(Auth::guard('api')->user());
});
//Route::group(['prefix' => '/user', 'middleware' => 'auth:api'], function () {
//
//});

//Route::middleware('auth:api')->get('/user', function (Request $request) {
//    return $request->user();
//});

Route::group(['prefix' => '/sessions', 'middleware' => 'api'], function () {
    Route::get('/all', function () {
        return new \App\Http\Resources\SessionCollection(\App\Session::all());
    })->name('sessions.all');
});
