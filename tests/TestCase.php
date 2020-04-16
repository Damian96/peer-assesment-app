<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\DB;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    /**
     * The callbacks that should be run before the application is destroyed.
     *
     * @var array
     */
    protected $beforeApplicationDestroyedCallbacks = [];

    public function __construct($name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
//        array_push($this->beforeApplicationDestroyedCallbacks, function () {
//            try {
//                $user = \App\User::whereEmail('jdoe@citycollege.sheffield.eu')->firstOrFail();
//                $user->delete();
//            } catch (\Throwable $e) {
////                throw $e;
//            }
//
//            try {
//                dd(DB::connection(env('DB_CONNECTION')));
//            } catch (\Exception $e) {
//                throw $e;
//            }
//        });
    }
}
