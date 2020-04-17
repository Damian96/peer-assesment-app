<?php

namespace Tests\Feature;

use Tests\TestCase;

class RegisterTest extends TestCase
{
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
            'localhost' => env('APP_ENV', 'local') === 'local' ? '1' : '0',
        ]);

        $response->assertLocation('/login');
    }

    /**
     * Tests
     * @return void
     */
//    public function testUnsuccessful()
//    {
//        $response = $this->post('/store', [
//            '_method' => 'POST',
//            '_token' => csrf_token(),
//            'fname' => 'Joe',
//            'lname' => 'Doe',
//            'email' => 'jdoe@citycollege.sheffield.eu',
//            'reg_num' => 'CS204' . rand(0, 99),
//            'password' => '123',
//            'password_confirmation' => '123',
//            'department' => 'CS',
//            'localhost' => '1',
//        ]);
//
//        $response->assertLocation('/login');
//    }
}
