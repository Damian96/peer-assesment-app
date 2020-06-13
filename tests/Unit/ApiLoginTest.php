<?php


namespace Tests\Unit;


use Tests\TestCase;

class ApiLoginTest extends TestCase
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

    /**
     * Test the API Authentication endpoint for non-existing email
     * @return void
     */
    public function testUnsuccessfulAuthentication()
    {
        $response = $this->post('/api/user/login', [
            'method' => '_POST',
            'email' => 'jdoe@citycollege.sheffield.eu',
            'password' => 'loremipsum',
        ], [
            'Accept' => 'application/json',
        ]);

        $response->assertStatus(200);
        $response->assertSeeText('message');
        $response->assertSeeText('error');
    }

    /**
     * Test the API Authentication endpoint for invalid email-password combination
     * @return void
     */
    public function testInvalidComboAuthentication()
    {
        $response = $this->post('/api/user/login', [
            'method' => '_POST',
            'email' => 'dgiankakis@citycollege.sheffield.eu',
            'password' => 'iamjoedoefromskg',
        ], [
            'Accept' => 'application/json',
        ]);

        $response->assertStatus(200);
        $response->assertSeeText('message');
        $response->assertSeeText('error');
    }
}
