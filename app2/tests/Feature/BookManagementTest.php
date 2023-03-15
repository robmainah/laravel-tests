<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Book;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class BookManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_a_book_can_be_added_to_library(): void
    {
        $response = $this->post('/books', [
            'title' => 'book 1',
            'author' => 'James',
        ]);

        $book = Book::first();

        $this->assertCount(1, Book::all());
        $response->assertRedirect($book->path());
    }

    public function test_a_title_is_required()
    {
        $this->withExceptionHandling();

        $response = $this->post('/books', [
            'title' => '',
            'author' => 'James',
        ]);

        $response->assertSessionHasErrors('title');
    }

    public function test_an_author_is_required()
    {
        $this->withExceptionHandling();
         
        $response = $this->post('/books', [
            'title' => 'title 1',
            'author' => '',
        ]);

        $response->assertSessionHasErrors('author');
    }

    public function test_a_book_can_be_updated()
    {
        $this->post('/books', [
            'title' => 'book 1',
            'author' => 'James',
        ]);

        $book = Book::first();

        $response = $this->patch($book->path(), [
            'title' => 'book 1 updated',
            'author' => 'James updated',
        ]);

        $book = $book->fresh();

        $this->assertEquals('book 1 updated', $book->title);
        $this->assertEquals('James updated', $book->author);
        $response->assertRedirect($book->path());
    }

    public function test_a_book_can_be_deleted() 
    {
        $this->post('/books', [
            'title' => 'book 1',
            'author' => 'James',
        ]);

        $book = Book::first();
        $this->assertCount(1, Book::all());

        $response = $this->delete($book->path(), [
            'title' => 'book 1 updated',
            'author' => 'James updated',
        ]);

        $this->assertCount(0, Book::all());
        $response->assertRedirect('/books');
    }
}
