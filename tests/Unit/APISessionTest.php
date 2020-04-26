<?php


namespace Tests\Unit;


use App\User;
use Tests\TestCase;

class APISessionTest extends TestCase
{
    /**
     * /api/sessions/{id}
     * Test whether an existing Session is available through the API
     * @return void
     */
//    public function testSingleSessionResource()
//    {
//    }

    /**
     * @var null|string
     */
    private $api_token;

    /**
     * /api/sessions/all
     * Test whether all Session(s) are retrievable by the API
     * @return void
     */
    public function testSessionCollection()
    {
        $response = $this->get('/api/sessions/all', [
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
