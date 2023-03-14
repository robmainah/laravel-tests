<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Book;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class BookReservationTest extends TestCase
{
    use RefreshDatabase;

    public function test_a_book_can_be_added_to_library(): void
    {
        $response = $this->post('/books', [
            'title' => 'book 1',
            'author' => 'James',
        ]);

        $response->assertCreated();

        $this->assertCount(1, Book::all());
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

        $this->patch("/books/{$book->id}", [
            'title' => 'book 1 updated',
            'author' => 'James updated',
        ]);

        $this->assertEquals('book 1 updated', Book::first()->title);
        $this->assertEquals('James updated', Book::first()->author);
    }
}
