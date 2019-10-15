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
Route::namespace('Auth')->group(function () {
    Route::match(['get', 'post'], '/login', 'LoginController@login')->name('user.login');
//    Route::match(['get', 'post'], '/login', function () {
//            return view('user.login', ['title' => 'Student Login']);
//    });
});
Route::namespace('User')->group(function() {
    Route::match(['get'], '/home', 'UserController@home')->name('user.home');
});
