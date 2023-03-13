<?php

namespace Tests;

use App\Models\Label;
use App\Models\Task;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use App\Models\TodoList;
use App\Models\User;
use App\Models\Service;
use Laravel\Sanctum\Sanctum;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    public function setUp(): void
    {
        parent::setUp();

        $this->withoutExceptionHandling();
    }

    public function createTodoList($args = [])
    {
        return TodoList::factory()->create($args);
    }

    public function createTask($args = [])
    {
        return Task::factory()->create($args);
    }

    public function createUser()
    {
        return User::factory()->create();
    }

    public function createLabel($args = [])
    {
        return Label::factory()->create($args);
    }

    public function createService($args = [])
    {
        return Service::factory()->create($args);
    }

    public function authUser()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        return $user;
    }
}
