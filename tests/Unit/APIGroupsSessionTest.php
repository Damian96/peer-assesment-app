<?php

namespace Tests\Feature;

use Tests\TestCase;

class APIGroupsSessionTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testSuccessful()
    {
        $response = $this->get('/api/groups/1/all', [
            'Accept' => 'application/json'
        ]);

        $response->assertStatus(200);
    }
}
