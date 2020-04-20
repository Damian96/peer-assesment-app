<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

/**
 * @property void deleteTransactions
 */
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
        array_push($this->beforeApplicationDestroyedCallbacks, array(self::class, 'deleteTransactions'));
    }

    /**
     * Delete all transactions made during the test
     * @return void
     * @throws \Throwable
     */
    public static function deleteTransactions()
    {
        try {
            $user = \App\User::whereEmail('joedoe@' . config('app.domain'))->firstOrFail();
            $user->delete();
        } catch (\Throwable $e) {
            //            throw_if(env('APP_DEBUG', false), $e);
            return;
        }
    }
}
