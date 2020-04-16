<?php


namespace Tests\Unit;


use Tests\TestCase;

class APILoginTest extends TestCase
{
    /**
     * Tests the API Authentication endpoint
     * @return void
     */
    public function testSuccessfulAuthentication()
    {
        $response = $this->post('/api/user/login', [
            'method' => '_POST',
            'email' => 'dgiankakis@citycollege.sheffield.eu',
            'password' => 'everse2309',
        ], [
            'Accept' => 'application/json',
        ]);
        
        $response->assertStatus(200);
    }
}
