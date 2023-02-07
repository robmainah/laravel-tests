<?php

namespace Tests\Feature;

use App\Models\Book;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BookReservationTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function test_a_book_can_be_added()
    {
        // $this->withoutExceptionHandling();
        $response = $this->post('/books', [
            'title' => 'book 1',
            'author' => 'James Clear',
        ]);

        $response->assertOK();
        $this->assertCount(1, Book::all());
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
    public function a_book_can_be_updated()
    {
        $this->withoutExceptionHandling();
        $this->post('/books', [
            'title' => 'title 1',
            'author' => 'john',
        ]);

        $book = Book::first();

        $response = $this->patch('/books/' . $book->id, [
            'title' => 'New title',
            'author' => 'New author',
        ]);

        $this->assertEquals('New title', Book::first()->title);
        $this->assertEquals('New author', Book::first()->author);
    }
}
