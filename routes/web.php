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

Route::get('/', function () {
    return view('welcome');
});
Route::namespace('User')->group(function() {
    Route::match(['get'], '/home', 'UserController@home')->name('user.home');
    Route::match(['get', 'post'], '/login', 'UserController@login')->name('user.login');
    Route::match(['get', 'post'], '/register', 'UserController@register')->name('user.register');
    Route::match(['get'], '/logout', 'UserController@logout')->name('user.logout');
    Route::match(['get'], '/verify', 'UserController@verify')->name('user.verify');
    Route::match(['get'], '/profile', 'UserController@profile')->name('user.profile');
});
Route::match(['get', 'post'], '/course/create', 'CourseController@create')->name('course.create');
