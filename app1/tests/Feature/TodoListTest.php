<?php

namespace Tests\Feature;

use App\Models\TodoList;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

use function PHPUnit\Framework\assertEquals;
use App\Models\User;
use Laravel\Sanctum\Sanctum;

class TodoListTest extends TestCase
{
    use RefreshDatabase;

    private $list;
    private $user;

    public function setUp(): void
    {
        parent::setUp();

        $this->user = $this->authUser();
        $this->list = $this->createTodoList([
                'name' => 'my list',
                'user_id' => $this->user->id,
            ]);
    }

    public function test_fetch_user_todo_list(): void
    {
        $this->createTodoList();
        $response = $this->getJson(route('todo-lists.index'))->json();

        $this->assertEquals(1, count($response));
        $this->assertEquals('my list', $response[0]['name']);
    }

    public function test_fetch_single_todo_list(): void
    {
        $response = $this->getJson(route('todo-lists.show', $this->list->id))
            ->assertOk()
            ->json('data');

        $this->assertEquals($response['name'], $this->list->name);
    }

    public function test_store_new_todo_list(): void
    {
        $list = TodoList::factory()->make();

        $response = $this->postJson(route('todo-lists.store', ['name' => $list->name]))
            ->assertCreated()
            ->json('data');

        $this->assertDatabaseHas('todo_lists', ['name' => $list->name]);
        $this->assertEquals($list->name, $response['name']);
    }

    public function test_while_storing_name_is_required(): void
    {
        $this->withExceptionHandling();
        $this->postJson(route('todo-lists.store'))
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['name']);
    }

    public function test_update_todo_list(): void
    {
        $response = $this->putJson(route('todo-lists.update', $this->list->id), [
                'name' => 'updated name',
                'user_id' => $this->user->id,
            ])
            ->assertOk()
            ->json('data');

        $this->assertEquals('updated name', $response['name']);
        $this->assertDatabaseHas('todo_lists', ['name' => 'updated name']);
    }

    public function test_while_updating_name_is_required(): void
    {
        $this->withExceptionHandling();
        $this->putJson(route('todo-lists.update', $this->list->id))
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['name']);
    }

    public function test_delete_todo_list(): void
    {
        $this->deleteJson(route('todo-lists.destroy', $this->list->id))
            ->assertNoContent();

        $this->assertDatabaseMissing('todo_lists', ['name' => $this->list->name]);
    }
}
