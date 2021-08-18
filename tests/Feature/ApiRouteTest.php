<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;

class ApiRouteTest extends \Tests\TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function test_graphql()
    {
        $response = $this->get('/api');

        $response->assertStatus(200);
    }
}
