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

Route::match(['get'], '/register', 'UserController@create')->name('user.register');
Route::match(['get'], '/register', 'UserController@create')->name('register');

Route::match(['post'], '/store', 'UserController@store')->name('user.store');
Route::match(['put'], '/update', 'UserController@update')->name('user.update');
Route::match(['get'], '/profile', 'UserController@profile')->name('user.profile');
Route::match(['put'], '/config/store', 'UserController@storeConfig')->name('user.config');
Route::match(['get'], '/password/forgot', 'UserController@forgot')->name('user.forgot');
Route::match(['post'], '/password/send', 'UserController@forgotSend')->name('user.forgotSend');
Route::match(['get'], '/password/reset', 'UserController@reset')->name('user.reset');

Route::match(['get'], '/verify', 'UserController@verify')->name('user.verify');
Route::match(['get'], '/login', 'UserController@login')->name('user.login');
Route::match(['get'], '/login', 'UserController@login')->name('login');
Route::match(['post'], '/auth', 'UserController@auth')->name('user.auth');
Route::match(['get'], '/logout', 'UserController@logout')->name('user.logout');
Route::match(['get'], '/attribution', 'UserController@attribution')->name('user.attribution');

//Route::group(['prefix' => '/errors'], function() {
//    Route::get('/503', 'ErrorController@error503')->name('errors.503');
//});

Route::group(['prefix' => '/users'], function () {
    Route::match(['get'], '/verified', 'UserController@verified')->name('verification.notice');
    Route::match(['get'], '{user}/show', 'UserController@show')->name('user.show');
    Route::match(['get'], '/show/{user}', 'UserController@show')->name('user.show');
});

# COURSE
Route::group(['prefix' => '/courses'], function () {
    Route::get('', 'CourseController@index')->name('course.index');
    Route::get('create', 'CourseController@create')->name('course.create');
    Route::get('{course}/edit', 'CourseController@edit')->name('course.edit');
    Route::match(['put'], '{course}', 'CourseController@update')->name('course.update');
    Route::match(['post'], '/store  ', 'CourseController@store')->name('course.store');
    Route::match(['delete'], '/{course}/delete', 'CourseController@destroy')->name('course.destroy');
    Route::match(['delete'], '/{course}/disenroll', 'CourseController@disenroll')->name('course.disenroll');
    Route::match(['post'], '/{course}/duplicate', 'CourseController@copy')->name('course.copy');
    Route::match(['get'], '{course}/view', 'CourseController@show')->name('course.view');
    Route::match(['get'], '{course}/students', 'CourseController@students')->name('course.students');
    Route::match(['get'], '{course}/add-student', 'UserController@addStudent')->name('course.add-student');
    Route::match(['post'], '{course}/store-student', 'UserController@storeStudent')->name('user.store-student');
//    Route::get('search', 'CourseController@search')->name('course.search');
});

# Session
Route::match(['get'], '/sessions', 'SessionController@index')->name('session.index');
Route::group(['prefix' => '/sessions'], function () {
    Route::match(['get'], 'create/{course?}', 'SessionController@create')->name('session.create');
    Route::match(['post'], '/store', 'SessionController@store')->name('session.store');
    Route::match(['post'], '{session}/add-group', 'SessionController@addGroup')->name('session.addGroup');
    Route::match(['post'], '{session}/join-group', 'SessionController@joinGroup')->name('session.joinGroup');
    Route::match(['get'], '{session}/view', 'SessionController@show')->name('session.view');
    Route::match(['get'], '{session}/fill', 'SessionController@fill')->name('session.fill');
//    Route::match(['get'], '{session}/refill', 'SessionController@refill')->name('session.refill');
    Route::match(['post'], '{session}/fillin', 'SessionController@fillin')->name('session.fillin');
    Route::match(['get'], '{session}/delete', 'SessionController@delete')->name('session.delete');
    Route::match(['get'], '{session}/edit', 'SessionController@edit')->name('session.edit');
    Route::match(['get'], '{session}/mark', 'SessionController@mark')->name('session.mark');
    Route::match(['get'], '{session}/remark', 'SessionController@mark')->name('session.mark');
    Route::match(['post'], '{session}/update', 'SessionController@update')->name('session.update');
    Route::match(['delete'], '{session}/delete', 'SessionController@delete')->name('session.delete');
    Route::match(['get'], '{session}/feedback/{student}', 'SessionController@feedback')->name('session.feedback');
});

Route::group(['prefix' => '/forms'], function () {
    Route::match(['get'], '/', 'FormController@index')->name('form.index');
    Route::match(['get'], '/create', 'FormController@create')->name('form.create');
    Route::match(['post'], '/store', 'FormController@store')->name('form.store');
    Route::match(['post'], '{form}/update', 'FormController@update')->name('form.update');
    Route::match(['get'], '/{form}/edit', 'FormController@edit')->name('form.edit');
    Route::match(['post'], '/preview', 'FormController@preview')->name('form.preview');
    Route::delete('/{form}/delete', 'FormController@delete')->name('form.delete');
    Route::post('/{form}/trash', 'FormController@trash')->name('form.trash');
    Route::match(['post'], '/{form}/duplicate', 'FormController@duplicate')->name('form.duplicate');
});

Route::group(['prefix' => '/groups'], function () {
//    Route::match(['get'], '/{group}', 'GroupController@show')->name('group.show');
    Route::post('/{group}/store-mark', 'GroupController@storeMark')->name('group.show');
    Route::get('/{session}/form', 'ApiSessionsController@getSessionForm')->name('api.sessionForm');
});
