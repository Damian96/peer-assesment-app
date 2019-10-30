<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

# USER
Route::match(['get'], '/', 'UserController@index')->name('user.home');
Route::match(['get'], '/home', 'UserController@index')->name('user.home');
Route::match(['get'], '/index', 'UserController@index')->name('user.home');

// FIXME: make private
Route::match(['get'], '/register', 'UserController@create')->name('user.register');

Route::match(['post'], '/store', 'UserController@store')->name('user.store');
Route::match(['put'], '/update', 'UserController@update')->name('user.update');
Route::match(['get'], '/profile', 'UserController@show')->name('user.profile');
Route::match(['get'], '/password/forgot', 'UserController@forgot')->name('user.forgot');
Route::match(['post'], '/password/send', 'UserController@forgotSend')->name('user.forgotSend');
Route::match(['get'], '/password/reset', 'UserController@reset')->name('user.reset');

Route::match(['get'], '/verify', 'UserController@verify')->name('user.verify');
Route::match(['get', 'post'], '/login', 'UserController@login')->name('user.login');
Route::match(['get'], '/logout', 'UserController@logout')->name('user.logout');

# COURSE
Route::match(['get'], '/courses', 'CourseController@index')->name('course.index');
Route::group(['prefix' => '/courses'], function() {
    Route::match(['get'], 'create', 'CourseController@create')->name('course.create');
    Route::match(['get'], '{id}/edit', 'CourseController@edit')->where('id', '[0-9]+')->name('course.edit');
    Route::match(['put'], '{id}', 'CourseController@update')->where('id', '[0-9]+')->name('course.update');
    Route::match(['post'], '/store  ', 'CourseController@store')->name('user.store');
    Route::match(['get'], 'view/{id}', 'CourseController@show')->where('id', '[0-9]+')->name('course.view');
//    Route::match(['get'], '{id}', 'CourseController@course')->where('id', '[0-9]+')->name('course.edit');
});
