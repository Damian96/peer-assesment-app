<?php

namespace Tests\Feature;

use App\User;
use Tests\TestCase;

class APIGroupsSessionTest extends TestCase
{
    /**
     * @var null|string
     */
    private $api_token;

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testSuccessful()
    {
        $response = $this->get('/api/groups/1/all', [
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
