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

Route::group(['prefix' => '/fallback'], function () {
    Route::get('/404', 'ApiController@error404')->name('api.fallback.404');
    Route::get('/500', 'ApiController@error500')->name('api.fallback.500');
});

Route::middleware('api')->get('/user', function (Request $request) {
    return json_encode(Auth::guard('api')->user());
});
Route::group(['prefix' => '/user'], function () {
    Route::post('/login', 'ApiController@login')->name('api.login');
});

Route::group(['prefix' => '/sessions', 'middleware' => 'api'], function () {
    Route::get('/all', 'ApiController@sessionCollection')->name('api.sessionCollection');
});

Route::group(['prefix' => '/groups', 'middleware' => 'api'], function () {
    Route::get('{session}/all', function (Request $request, \App\Session $session) {
        /**
         * @var \App\Session $session
         */
        $groups = new \App\Http\Resources\GroupCollection($session->groups()->getModels());
        $groups->additional(['session' => $session]);
        return $groups;
    });
    Route::get('{session}/form', function (Request $request, \App\Session $session) {
        /**
         * @var \App\Session $session
         */
        $groups = $session->groups()->getModels();
        $select = html()->select('group_id')->addClass('form-control-md')->attribute('required', 'true')->attribute('aria-required', 'true');

        foreach ($groups as $model) {
            /**
             * @var \App\Group $model
             */
            $select = $select->addChild(html()->option($model->name, $model->id));
        }

        $output = html()->form('POST', url("/sessions/{$session->id}/join-group"))
            ->addChild(html()->div(html()->label('Group')->addClass('form-control-md mr-2'))->addClass('form-group text-center mt-1')
                ->addChild($select)
                ->addChild(html()->span()->attribute('class', 'invalid-feedback d-block'))
                ->addChild(html()->input('hidden', 'session_id', $session->id))
                ->addChild(html()->input('hidden', '_method', 'POST'))
                ->addChild(csrf_field()));

        return $output;
    });
    Route::get('/all', function (Request $request) {
        return new \App\Http\Resources\GroupCollection(\App\Group::all()->collect());
    })->name('sessions.all');
});
