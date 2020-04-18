<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class RegisterTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * Tests a successful Instructor user register attempt
     * @return void
     */
    public function testSuccessful()
    {
        $response = $this->post('/store', [
            '_method' => 'POST',
            '_token' => csrf_token(),
            'fname' => 'Joe',
            'lname' => 'Doe',
            'email' => 'joedoe',
            'reg_num' => 'CS2003' . rand(0, 9),
            'password' => '123',
            'password_confirmation' => '123',
            'department' => 'CS',
            'localhost' => '1',
        ]);

        $response->assertLocation('/login');
    }

    /**
     * Tests
     * @return void
     */
    public function testUnsuccessful()
    {
        $response = $this->post('/store', [
            '_method' => 'POST',
            '_token' => csrf_token(),
            'fname' => 'Joe',
            'lname' => 'Doe',
            'email' => 'jdoe@gmail.com',
            'reg_num' => 'ABC04' . rand(0, 99),
            'password' => '123',
            'password_confirmation' => '123',
            'department' => 'CS',
            'localhost' => '1',
        ]);

        $response->assertLocation('/register');
    }
}
