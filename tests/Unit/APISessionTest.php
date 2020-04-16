<?php


namespace Tests\Unit;


use Tests\TestCase;

class APISessionTest extends TestCase
{
    /**
     * /api/sessions/{id}
     * Test whether an existing Session is available through the API
     * @return void
     */
//    public function testSingleSessionResource()
//    {
//    }

    /**
     * /api/sessions/all
     * Test whether all Session(s) are retrievable by the API
     * @return void
     */
    public function testSessionCollection()
    {
        $response = $this->get('/api/sessions/all', [
            'Accept' => 'application/json'
        ]);

        $response->assertStatus(200);
    }

}
