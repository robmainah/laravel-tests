<?php

namespace Tests\Unit;

use App\Models\User;
use Tests\TestCase;

class UserTest extends TestCase
{
    /**
     * A basic unit test example.
     */
    public function test_login_form(): void
    {
        $response = $this->get('/login');
        $response->assertStatus(200);
    }

    public function test_user_duplication()
    {
        $user1 = User::make([
            'name' => 'John Doe',
            'email' => 'john@doe.com'
        ]);

        $user2 = User::make([
            'name' => 'Dary',
            'email' => 'dary@doe.com'
        ]);

        $this->assertTrue($user1->name != $user2->name);
    }

    public function test_delete_user()
    {
        $user = User::factory()->count(1)->make();

        $user = User::first();

        if ($user) {
            $user->delete();
        }

        $this->assertTrue(true);
    }

    public function test_stores_new_user()
    {
        $response = $this->post('/register', [
            'name' => 'John',
            'email' => 'john@doe.com',
            'password' => '12345678',
            'password_confirmation' => '12345678',
        ]);

        $response->assertRedirect('/home');
    }

    public function test_has_database()
    {
        $this->assertDatabaseHas('users', [
            'name' => 'John',
        ]);
    }

    public function test_missing_database()
    {
        $this->assertDatabaseMissing('users', [
            'name' => 'Mary',
        ]);
    }

    public function test_if_seeders_works()
    {
        $this->seed();
    }
}
