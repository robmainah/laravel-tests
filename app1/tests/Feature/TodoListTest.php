<?php

namespace Tests\Feature;

use App\Models\TodoList;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

use function PHPUnit\Framework\assertEquals;

class TodoListTest extends TestCase
{
    use RefreshDatabase;

    private $list;

    public function setUp(): void
    {
        parent::setUp();

        $this->list = TodoList::factory()->create(['name' => 'my list']);
    }

    public function test_fetch_todo_list(): void
    {
        $response = $this->getJson(route('todo-list.index'));

        $this->assertEquals(1, count($response->json()));
        $this->assertEquals('my list', $response->json()[0]['name']);
    }

    public function test_fetch_single_todo_list(): void
    {
        $response = $this->getJson(route('todo-list.show', $this->list->id));

        $response->assertOk();
        $this->assertEquals($response->json()['name'], $this->list->name);
    }

    public function test_store_new_todo_list(): void
    {
        $list = TodoList::factory()->make();

        $response = $this->postJson(route('todo-list.store', ['name' => $list->name]));

        $response->assertCreated();
        $this->assertDatabaseHas('todo_lists', ['name' => $list->name]);
        $this->assertEquals($list->name, $response->json()['name']);
    }

    public function test_while_storing_name_is_required(): void
    {
        $this->withExceptionHandling();
        $this->postJson(route('todo-list.store'))
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['name']);
    }

    public function test_update_todo_list(): void
    {
        $response = $this->postJson(route('todo-list.update', $this->list->id), ['name' => 'updated name']);

        $response->assertOk();
        $this->assertEquals('updated name', $response->json()['name']);

        $this->assertDatabaseHas('todo_lists', ['id' => $this->list->id, 'name' => 'updated name']);
    }

    public function test_while_updating_name_is_required(): void
    {
        $this->withExceptionHandling();
        $this->postJson(route('todo-list.update', $this->list->id))
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['name']);
    }

    public function test_delete_todo_list(): void
    {
        $this->deleteJson(route('todo-list.destroy', $this->list->id))
            ->assertNoContent();

        $this->assertDatabaseMissing('todo_lists', ['name' => $this->list->name]);
    }
}
