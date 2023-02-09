<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Book;
use App\Models\Author;
use Illuminate\Foundation\Testing\RefreshDatabase;

class BookManagementTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function test_a_book_can_be_added()
    {
        $response = $this->post('/books', $this->data());

        $book = Book::first();

        // $response->assertOK();
        $this->assertCount(1, Book::all());
        $response->assertRedirect($book->path());
    }

    /** @test */
    public function test_a_title_is_required()
    {
        $response = $this->post('/books', [
            'title' => '',
            'author' => 'James Clear',
        ]);

        $response->assertSessionHasErrors('title');
    }

    /** @test */
    public function test_a_author_is_required()
    {
        $response = $this->post('/books', array_merge($this->data(), ['author_id' => '']));

        $response->assertSessionHasErrors('author_id');
    }

    /** @test */
    public function test_a_book_can_be_updated()
    {
        $this->post('/books', $this->data());

        $book = Book::first();

        $response = $this->patch($book->path(), [
            'title' => 'New title',
            'author_id' => 'New author',
        ]);

        $this->assertEquals('New title', Book::first()->title);
        $this->assertEquals(2, Book::first()->author_id);
        $response->assertRedirect($book->path());
    }

    /** @test */
    public function test_a_book_can_be_deleted()
    {
        $this->post('/books', $this->data());

        $book = Book::first();
        $this->assertCount(1, Book::all());

        $response = $this->delete($book->path());

        $this->assertCount(0, Book::all());
        $response->assertRedirect('/books');
    }

    public function test_a_new_author_is_automatically_added()
    {
        $this->post('/books', [
            'title' => 'title 1',
            'author_id' => 'john',
        ]);

        $book = Book::first();
        $author = Author::first();

        $this->assertEquals($author->id, $book->author_id);
        $this->assertCount(1, Author::all());
    }

    private function data(): array
    {
        return [
            'title' => 'book 1',
            'author_id' => 'James Clear',
        ];
    }
}
