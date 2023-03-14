<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Label;

class LabelTest extends TestCase
{
    use RefreshDatabase;

    private $user;

    public function setUp(): void
    {
        parent::setUp();
        $this->user = $this->authUser();
    }

    public function test_user_can_create_new_label(): void
    {
        $label = Label::factory()->raw();

        $this->postJson(route('labels.store', $label))
            ->assertCreated();

        $this->assertDatabaseHas('labels', ['title' => $label['title']]);
    }

    public function test_user_can_delete_label()
    {
        $label = $this->createLabel();

        $this->deleteJson(route('labels.destroy', $label->id))
            ->assertNoContent();

        $this->assertDatabaseMissing('labels', ['title' => $label['title']]);
    }

    public function test_user_can_update_label()
    {
        $label = $this->createLabel();

        $this->patchJson(route('labels.update', $label->id), [
            'title' => $label->title,
            'color' => 'new color'
        ])
        ->assertOk();

        $this->assertDatabaseHas('labels', ['color' => 'new color']);
    }

    public function test_fetch_a_users_labels(): void
    {
        $label = $this->createLabel(['user_id' => $this->user->id]);
        $this->createLabel();

        $response = $this->getJson(route('labels.index'))
            ->assertOk()
            ->json('data');
        
        $this->assertEquals($response[0]['title'], $label->title);
    }
}
