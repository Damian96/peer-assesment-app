<?php


namespace Tests\Unit;

use Tests\TestCase;

class UserVerifyTest extends TestCase
{
    /**
     * @return void
     */
    public function testSuccessfulVerification()
    {
        $query = http_build_query([
            'id' => 3,
            'hash' => '603a2cf9275b6953025beb6952f3c7f2bb5f00cd',
            'expires' => '1589802815',
            'action' => 'email',
        ]);
        $response = $this->get('/verify?' . $query, [
            'Accept' => 'application/json',
        ]);

        $response->assertStatus(302);
    }

    /**
     * @return void
     */
    public function testUnsuccessfulVerification()
    {
        $query = http_build_query([
            'id' => 3,
            'hash' => '603a2cf9275b6953025beb6952f3c7f2bb5f00ab',
            'expires' => '1589802816',
            'action' => 'email',
        ]);
        $response = $this->get('/verify?' . $query, [
            'Accept' => 'application/json',
        ]);

        $response->assertStatus(401);
    }
}
