<?php

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

// Route::middleware('api')->get('/user', function (Request $request) {
//     return json_encode(Auth::guard('api')->user());
// });
Route::group(['prefix' => '/user'], function () {
    Route::post('/login', 'ApiController@login')->name('api.login');
});

Route::group(['prefix' => '/sessions'], function () {
    Route::get('/all', 'ApiSessionsController@getAll')->name('api.sessionCollection');
    Route::get('/check', 'ApiSessionsController@checkSessions')->name('api.checkSessions');
});

Route::group(['prefix' => '/groups'], function () {
    Route::get('/{session}/all', 'ApiGroupsController@getSessionGroups')->name('api.groupsAll');
    Route::get('/all', 'ApiGroupsController@getAll')->name('api.sessionForm');
});
