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

Route::group(['prefix' => '/fallback'], function () {
    Route::get('/404', 'ApiController@error404')->name('api.fallback.404');
    Route::get('/500', 'ApiController@error500')->name('api.fallback.500');
});

Route::group(['prefix' => '/user'], function () {
    Route::post('/login', 'ApiController@login')->name('api.user.login');
    Route::get('/check', 'ApiController@check')->name('api.user.check');
});

Route::group(['prefix' => '/sessions'], function () {
    Route::get('/all', 'ApiController@sessionCollection')->name('api.sessions.collection');
});

Route::group(['prefix' => '/groups', 'middleware' => 'api'], function () {
    Route::get('{session}/all', 'ApiController@groupsOfSession')->name('api.groups.groupsOfSession');
    Route::get('{session}/form', 'ApiController@formOfSessionsGroups')->name('api.groups.form');
    Route::get('/all', 'ApiController@groups')->name('sessions.all');
});
