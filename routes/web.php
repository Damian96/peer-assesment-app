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
Route::match(['get', 'post'], '/login', 'UserController@login')->name('user.login');
Route::match(['get'], '/register', 'UserController@create')->name('user.register');
Route::match(['post'], '/store', 'UserController@store')->name('user.store');
Route::match(['get'], '/profile', 'UserController@show')->name('user.profile');
Route::match(['get'], '/verify', 'UserController@verify')->name('user.verify');
Route::match(['get'], '/logout', 'UserController@logout')->name('user.logout');

# COURSE
Route::match(['get'], '/courses', 'CourseController@index')->name('course.index');
Route::group(['prefix' => '/course'], function() {
    Route::match(['get', 'post'], 'create', 'CourseController@create')->name('course.create');
    Route::match(['get', 'post'], 'edit/{id}', 'CourseController@edit')->where('id', '[0-9]+')->name('course.edit');
//    Route::match(['get'], '{id}', 'CourseController@course')->where('id', '[0-9]+')->name('course.edit');
});
