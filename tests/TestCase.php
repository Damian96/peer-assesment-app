<?php

namespace Tests;

use App\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

/**
 * @property void deleteTransactions\
 * @property string api_token
 */
abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    private $api_token;

    /**
     * The callbacks that should be run after the application is created.
     *
     * @var array
     */
    protected $afterApplicationCreatedCallbacks = [];

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
        array_push($this->afterApplicationCreatedCallbacks, array(self::class, 'getApiToken'));
    }

    /**
     * Delete all transactions made during the test
     * @return void
     * @throws \Throwable
     */
    public static function deleteTransactions()
    {
        try {
            $user = User::whereEmail('joedoe@' . config('app.domain'))->firstOrFail();
            $user->delete();
        } catch (\Throwable $e) {
            //            throw_if(env('APP_DEBUG', false), $e);
            return;
        }
    }


    public static function getApiToken()
    {
        try {
            $user = User::whereId(1)->where('admin', '=', '1')->firstOrFail();
        } catch (ModelNotFoundException $e) {
            throw_if(config('env.APP_DEBUG'), $e, ['message' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
        }
    }
}
