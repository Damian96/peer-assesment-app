<?php

namespace Tests\Unit;

use App\Group;
use App\User;
use Illuminate\Support\Facades\Log;

use Tests\TestCase;

/**
 * Class ApiGroupsTest
 * @package Tests\Unit
 */
class ApiGroupsTest extends TestCase
{
    protected $defaultHeaders = [
        'Accept' => 'application/json',
    ];

    /**
     * @var \App\User
     */
    private $user;

    /**
     * Setup the test environment.
     *
     * @return void
     * @throws \Throwable
     */
    public function setUp(): void
    {
        parent::setUp();

        try {
            $this->user = factory(User::class)->make([
                'instructor' => 0,
                'admin' => 1,
            ]);
            $this->user->saveOrFail();
        } catch (\Throwable $e) {
            Log::error(sprintf("Failed to setUp %s", __CLASS__), ['message' => $e->getMessage()]);
            throw_if(config('env.APP_DEBUG'), $e, ['message' => $e->getMessage()]);
        }

        $this->defaultHeaders['Authorization'] = "Bearer {$this->user->api_token}";
    }

    /**
     * Test /api/groups/all endpoint.
     * @return void
     */
    public function testGetAll()
    {
        $response = $this->actingAs($this->user, 'api')
            ->get('/api/groups/all');

        $response->assertStatus(200);
        $response->assertDontSeeText('error');
    }

    /**
     * Test api/groups/{group}/all endpoint.
     * @return void
     */
    public function testGroupsOfSession()
    {
        if (!Group::whereId(1)->exists())
            return;

        $response = $this->actingAs($this->user, 'api')
            ->get('/api/groups/1/all');

        $response->assertStatus(200);
        $response->assertDontSeeText('error');
    }

    /**
     * Clean up the testing environment before the next test.
     *
     * @return void
     * @throws \Throwable
     */
    public function tearDown(): void
    {
        parent::tearDown();

        try {
            $this->user->delete();
        } catch (\Exception $e) {
            Log::error(sprintf("Failed to tearDown %s", __CLASS__), ['message' => $e->getMessage()]);
            throw_if(config('env.APP_DEBUG'), $e, ['message' => $e->getMessage()]);
        }
    }
}
