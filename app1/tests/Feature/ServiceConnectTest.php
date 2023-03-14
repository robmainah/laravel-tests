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

    private $user;

    public function setUp(): void
    {
        parent::setUp();
        $this->user = $this->authUser();
    }

    public function test_can_connect_to_google_drive_and_save_token(): void
    {
        $this->mock(Client::class, function (MockInterface $mock) {
            $mock->shouldReceive('addScope');
            $mock->shouldReceive('createAuthUrl')
                ->andReturn('http://localhost');
        });

        $response = $this->getJson(route('service.connect', 'google-drive'))
            ->assertOk()
            ->json();

        $this->assertEquals('http://localhost', $response['url']);
        $this->assertNotNull($response['url']);
    }

    public function test_servie_callback_will_store_token(): void
    {
        $this->mock(Client::class, function (MockInterface $mock) {
            $mock->shouldReceive('fetchAccessTokenWithAuthCode')
                ->andReturn(['access_token' => 'fake-token']);
        });

        $response = $this->postJson(route('service.callback'), [
                'code' => 'dummy',
            ])
            ->assertCreated();

        $this->assertDatabaseHas('services', [
            'user_id' => $this->user->id,
            'token->access_token' => 'fake-token',
        ]);
    }

    public function test_data_of_a_can_be_stored_on_google_drive()
    {
        $this->createTask(['created_at' => now()->subDays(2)]);
        $this->createTask(['created_at' => now()->subDays(3)]);
        $this->createTask(['created_at' => now()->subDays(4)]);
        $this->createTask(['created_at' => now()->subDays(6)]);

        $this->createTask(['created_at' => now()->subDays(10)]);

        $this->mock(Client::class, function (MockInterface $mock) {
            $mock->shouldReceive('setAccessToken');
            $mock->shouldReceive('shouldDefer');
            $mock->shouldReceive('execute');
            $mock->shouldReceive('getLogger->info');
        });
        
        $service = $this->createService();
        
        $this->postJson(route('service.store', $service->id))
            ->assertCreated();
    }
}
