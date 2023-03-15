<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Author;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AuthorManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_an_author_can_be_created(): void
    {
        $response = $this->post('/authors', [
            'name' => 'james',
            'dob' => '05/13/1988',
        ]);

        $author = Author::all();

        $this->assertCount(1, $author);
        $this->assertInstanceOf(Carbon::class, $author->first()->dob);
        $this->assertEquals('1988/05/13', $author->first()->dob->format('Y/m/d'));
    }
}
