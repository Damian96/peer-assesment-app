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
Route::match(['get'], '{user}/show', 'UserController@show')->name('user.show');
Route::match(['get'], '/profile', 'UserController@profile')->name('user.profile');
Route::match(['get'], '/password/forgot', 'UserController@forgot')->name('user.forgot');
Route::match(['post'], '/password/send', 'UserController@forgotSend')->name('user.forgotSend');
Route::match(['get'], '/password/reset', 'UserController@reset')->name('user.reset');

Route::match(['get'], '/verify', 'UserController@verify')->name('user.verify');
Route::match(['get'], '/login', 'UserController@login')->name('user.login');
Route::match(['post'], '/auth', 'UserController@auth')->name('user.auth');
Route::match(['get'], '/logout', 'UserController@logout')->name('user.logout');

# COURSE
Route::match(['get'], '/courses', 'CourseController@index')->name('course.index');
Route::group(['prefix' => '/courses'], function () {
    Route::match(['get'], 'create', 'CourseController@create')->name('course.create');
    Route::match(['get'], '{course}/edit', 'CourseController@edit')->name('course.edit');
    Route::match(['put'], '{id}', 'CourseController@update')->where('id', '[0-9]+')->name('course.update');
    Route::match(['post'], '/store  ', 'CourseController@store')->name('course.store');
    Route::match(['delete'], '/{course}/delete', 'CourseController@destroy')->name('course.destroy');
    Route::match(['post'], '/{course}/duplicate', 'CourseController@copy')->name('course.copy');
    Route::match(['get'], '{course}/view', 'CourseController@show')->name('course.view');
    Route::match(['get'], '{course}/students', 'CourseController@students')->name('course.students');
    Route::match(['get'], '{course}/add-student', 'UserController@addStudent')->name('course.add-student');
    Route::match(['post'], '{course}/store-student', 'UserController@storeStudent')->name('user.store-student');
});

# Session
Route::match(['get'], '/sessions', 'SessionController@active')->name('session.active');
Route::match(['get'], '/courses/{cid}/sessions', 'SessionController@index')->where('cid', '[0-9]+')->name('session.index');
Route::group(['prefix' => '/sessions'], function () {
    Route::match(['post'], 'form/store', 'SessionController@storeForm')->name('session.storeForm');
    Route::match(['get'], 'form/add', 'SessionController@addForm')->name('form.add');
    Route::match(['get'], 'create/{course?}', 'SessionController@create')->name('session.create');
    Route::match(['post'], '/store  ', 'SessionController@store')->name('session.store');
    Route::match(['get'], '{id}/view', 'SessionController@show')->where('id', '[0-9]+')->name('session.view');
//    Route::match(['get'], '{id}/edit', 'CourseController@edit')->where('id', '[0-9]+')->name('course.edit');
//    Route::match(['put'], '{id}', 'CourseController@update')->where('id', '[0-9]+')->name('course.update');
//    Route::match(['get'], '{id}', 'CourseController@course')->where('id', '[0-9]+')->name('course.edit');
});
