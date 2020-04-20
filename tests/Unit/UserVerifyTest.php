<?php


namespace Tests\Unit;

use App\User;
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
     * @throws \Throwable
     */
    public function testUnsuccessfulVerification()
    {
        try {
            $notifiable = User::whereId(3)->firstOrFail();
        } catch (\Throwable $e) {
            throw_if(env('APP_DEBUG', false), $e);
            return;
        }

        $query = http_build_query([
            'id' => $notifiable->id,
            'hash' => sha1($notifiable->getEmailForVerification()),
            'expires' => strtotime('+3 hours', now()),
            'action' => 'email',
        ]);
        $response = $this->get('/verify?' . $query, [
            'Accept' => 'application/json',
        ]);

        $response->assertStatus(401);
    }
}
