<?php

namespace Tests\Feature;

use App\Models\Book;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BookManagementTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function test_a_book_can_be_added()
    {
        $response = $this->post('/books', [
            'title' => 'book 1',
            'author' => 'James Clear',
        ]);

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
        $response = $this->post('/books', [
            'title' => 'title 1',
            'author' => '',
        ]);

        $response->assertSessionHasErrors('author');
    }

    /** @test */
    public function test_a_book_can_be_updated()
    {
        $this->post('/books', [
            'title' => 'title 1',
            'author' => 'john',
        ]);

        $book = Book::first();

        $response = $this->patch($book->path(), [
            'title' => 'New title',
            'author' => 'New author',
        ]);

        $this->assertEquals('New title', Book::first()->title);
        $this->assertEquals('New author', Book::first()->author);
        $response->assertRedirect($book->path());
    }

    /** @test */
    public function test_a_book_can_be_deleted()
    {
        $this->post('/books', [
            'title' => 'title 1',
            'author' => 'john',
        ]);

        $book = Book::first();
        $this->assertCount(1, Book::all());

        $response = $this->delete($book->path());

        $this->assertCount(0, Book::all());
        $response->assertRedirect('/books');
    }
}
