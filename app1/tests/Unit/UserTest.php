<?php

namespace Tests\Unit;

use App\Models\TodoList;
use tests\TestCase;

class UserTest extends TestCase
{
    /**
     * A basic unit test example.
     */
    public function test_user_has_many_todo_lists(): void
    {
        $user = $this->createUser();
        $this->createTodoList(['user_id' => $user->id]);

        $this->assertInstanceOf(TodoList::class, $user->todo_lists->first());
    }
}
