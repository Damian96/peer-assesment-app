<?php

namespace Tests\Feature;

use Tests\TestCase;

class LoginTest extends TestCase
{
    /**
     * @return void
     */
    public function testAuthentication()
    {
        $response = $this->post('/auth', [
            '_method' => 'POST',
            '_token' => csrf_token(),
            'email' => 'istamatopoulou@citycollege.sheffield.eu',
            'password' => '123',
            'remember' => 'on'
        ]);

        $response->assertStatus(302);
    }
}
