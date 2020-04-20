<?php


namespace Tests\Unit;


use Tests\TestCase;

class APIGroupsAllTest extends TestCase
{
    /**
     * Test /api/groups/all endpoint
     * @return void
     */
    public function testSuccessful()
    {
        $response = $this->get('/api/groups/all', [
            'Accept' => 'application/json',
        ]);

        $response->assertStatus(200);
    }
}
