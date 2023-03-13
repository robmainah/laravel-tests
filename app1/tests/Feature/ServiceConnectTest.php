<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Service;
use Mockery\MockInterface;
use Google\Client;

class ServiceConnectTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
        $this->user = $this->authUser();
    }

    public function test_can_connect_to_google_drive_and_save_token(): void
    {
        $response = $this->getJson(route('service.connect', 'google-drive'))
            ->assertOk()
            ->json();

        $this->assertNotNull($response['url']);
    }

    public function test_servie_callback_will_store_token(): void
    {
        $this->mock(Client::class, function (MockInterface $mock) {
            $mock->shouldReceive('setClientId')->once();
            $mock->shouldReceive('setClientSecret')->once();
            $mock->shouldReceive('setRedirectUri')->once();
            $mock->shouldReceive('fetchAccessTokenWithAuthCode')
                ->andReturn('fake-token');
        });

        $data = Service::factory()->make();

        $response = $this->postJson(route('service.callback'), $data->toArray())
            ->assertCreated();

        $this->assertDatabaseHas('services', [
            'user_id' => $this->user->id,
            'token->access_token' => 'fake-token',
        ]);
    }
}
