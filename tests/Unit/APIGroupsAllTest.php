<?php


namespace Tests\Unit;


use App\User;
use Tests\TestCase;

class APIGroupsAllTest extends TestCase
{
    /**
     * @var null|string
     */
    private $api_token;

    /**
     * Test /api/groups/all endpoint
     * @return void
     */
    public function testSuccessful()
    {
        $response = $this->get('/api/groups/all', [
            'Accept' => 'application/json',
            'api_token' => $this->api_token
        ]);

        $response->assertStatus(200);
    }

    /**
     * @throws \Throwable
     */
    protected function setUp(): void
    {
        parent::setUp();

        try {
            $user = User::whereId(1)->firstOrFail();
            $this->api_token = $user->api_token;
        } catch (\Throwable $e) {
            throw $e;
        }
    }
}
