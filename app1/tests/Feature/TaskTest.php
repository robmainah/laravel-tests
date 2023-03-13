<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Task;

class TaskTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->authUser();
    }

    public function test_fetch_all_tasks_of_a_todo_list(): void
    {
        $list = $this->createTodoList();
        $list2 = $this->createTodoList();
        $task = $this->createTask(['todo_list_id' => $list->id]);
        $this->createTask(['todo_list_id' => $list2->id]);

        $response = $this->getJson(route('todo-lists.tasks.index', $list->id))
            ->assertOk()
            ->json();

        $this->assertEquals(1, count($response));
        $this->assertEquals($task->title, $response[0]['title']);
    }

    public function test_store_a_task_for_a_todo_list()
    {
        $task = Task::factory()->make();
        $list = $this->createTodoList();
        $label = $this->createLabel();

        $this->postJson(route('todo-lists.tasks.store', $list->id), [
                'title' => $task->title,
                'label_id' => $label->id,
            ])
            ->assertCreated();

        $this->assertDatabaseHas('tasks', [
                'title' => $task->title,
                'todo_list_id' => $list->id,
                'label_id' => $label->id,
            ]);
    }

    public function test_store_a_task_for_a_todo_list_without_label()
    {
        $task = Task::factory()->make();
        $list = $this->createTodoList();

        $this->postJson(route('todo-lists.tasks.store', $list->id), [
                'title' => $task->title,
            ])
            ->assertCreated();

        $this->assertDatabaseHas('tasks', [
                'title' => $task->title,
                'todo_list_id' => $list->id,
                'label_id' => null,
            ]);
    }

    public function test_updating_a_task_for_a_todo_list()
    {
        $task = $this->createTask();

        $this->patchJson(route('tasks.update', $task->id), ['title' => 'updated title'])
            ->assertOk();

        $this->assertDatabaseHas('tasks', ['title' => 'updated title']);
    }

    public function test_deleting_a_task_for_a_todo_list()
    {
        $task = $this->createTask();

        $this->deleteJson(route('tasks.destroy', $task->id));
        $this->assertDatabaseMissing('tasks', ['title' => $task->title]);
    }

    public function test_a_task_status_can_be_changed(): void
    {
        $task = $this->createTask();
        $task['status'] = Task::STARTED;

        $this->patchJson(route('tasks.update', $task->id), $task->toArray());

        $this->assertDatabaseHas('tasks', ['status' => Task::STARTED]);
    }
}
