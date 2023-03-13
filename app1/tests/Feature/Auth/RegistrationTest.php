<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     */
    public function test_user_can_register(): void
    {
        $user = User::factory()->make();
        $this->postJson(route('user.register'), [
                'name' => $user->name,
                'email' => $user->email,
                'password' => $user->password,
                'password_confirmation' => $user->password,
            ])
            ->assertCreated();

        $this->assertDatabaseHas('users', ['name' => $user->name]);
    }
}
