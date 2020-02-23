<?php

namespace Tests\Feature;

use Tests\TestCase;

class RegisterTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testSuccessful()
    {
        $response = $this->post('/store', [
            '_method' => 'POST',
            '_token' => csrf_token(),
            'fname' => 'Joe',
            'lname' => 'Doe',
            'email' => 'jdoe@citycollege.sheffield.eu',
            'password' => '123',
            'department' => 'CS',
            'instructor' => '1',
            'localhost' => '1',
        ]);

        $response->assertLocation('/login');
    }
}
