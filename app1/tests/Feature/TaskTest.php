<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Task;

class TaskTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     */
    public function test_fetch_all_tasks_of_a_todo_list(): void
    {
        $list = $this->createTodoList();
        $task = $this->createTask(['todo_list_id' => $list->id]);
        $this->createTask(['todo_list_id' => 2]);

        $response = $this->getJson(route('todo-list.task.index', $list->id))
            ->assertOk()
            ->json();

        $this->assertEquals(1, count($response));
        $this->assertEquals($task->title, $response[0]['title']);
    }

    public function test_store_a_task_for_a_todo_list()
    {
        $task = Task::factory()->make();
        $list = $this->createTodoList();

        $this->postJson(route('todo-list.task.store', $list->id), ['title' => $task->title])
            ->assertCreated();

        $this->assertDatabaseHas('tasks', [
                'title' => $task->title,
                'todo_list_id' => $list->id,
            ]);
    }

    public function test_updating_a_task_for_a_todo_list()
    {
        $task = $this->createTask();

        $this->patchJson(route('task.update', $task->id), ['title' => 'updated title'])
            ->assertOk();

        $this->assertDatabaseHas('tasks', ['title' => 'updated title']);
    }

    public function test_deleting_a_task_for_a_todo_list()
    {
        $task = $this->createTask();

        $this->deleteJson(route('task.destroy', $task->id));
        $this->assertDatabaseMissing('tasks', ['title' => $task->title]);
    }
}