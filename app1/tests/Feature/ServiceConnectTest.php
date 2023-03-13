<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ServiceConnectTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_can_connect_to_google_drive_and_save_token(): void
    {
        $this->authUser();

        $response = $this->getJson(route('service.connect', 'google-drive'))
            ->assertOk()
            ->json();

        $this->assertNotNull($response['url']);
    }
}
