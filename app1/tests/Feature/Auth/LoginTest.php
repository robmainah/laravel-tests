<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;

class LoginTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_user_can_login(): void
    {
        $user = $this->createUser();
        $response = $this->postJson(route('user.login'), [
                'email' => $user->email,
                'password' => 'password',
            ])
            ->assertOk();

        $this->assertArrayHasKey('token', $response->json());
    }

    public function test_user_email_is_avalable_return_error()
    {
        $user = $this->createUser();
        $this->postJson(route('user.login'), [
                'email' => $user->email,
                'password' => $user->password,
            ])
            ->assertUnauthorized();
    }

    public function test_raise_error_if_password_is_incorrect()
    {
        $user = $this->createUser();
        $this->postJson(route('user.login'), [
                'email' => $user->email,
                'password' => 'random',
            ])
            ->assertUnauthorized();
    }
}
