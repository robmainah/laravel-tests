<?php

namespace Tests\Feature;

use App\Models\Author;
use Tests\TestCase;
use App\Models\Book;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class BookManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_a_book_can_be_added_to_library(): void
    {
        $response = $this->post('/books', $this->getData());

        $book = Book::first();

        $this->assertCount(1, Book::all());
        $response->assertRedirect($book->path());
    }

    public function test_a_title_is_required()
    {
        $this->withExceptionHandling();

        $response = $this->post('/books', array_merge($this->getData(), ['title' => '']));

        $response->assertSessionHasErrors('title');
    }

    public function test_an_author_is_required()
    {
        $this->withExceptionHandling();
         
        $response = $this->post('/books', array_merge($this->getData(), ['author_id' => '']));

        $response->assertSessionHasErrors('author_id');
    }

    public function test_a_book_can_be_updated()
    {
        $this->post('/books', $this->getData());

        $book = Book::first();

        $response = $this->patch($book->path(), [
            'title' => 'book 1 updated',
            'author_id' => 'James updated',
        ]);

        $book = $book->fresh();

        $this->assertEquals('book 1 updated', $book->title);
        $this->assertEquals(2, $book->author_id);
        $response->assertRedirect($book->path());
    }

    public function test_a_book_can_be_deleted() 
    {
        $this->post('/books', $this->getData());

        $book = Book::first();
        $this->assertCount(1, Book::all());

        $response = $this->delete($book->path());

        $this->assertCount(0, Book::all());
        $response->assertRedirect('/books');
    }

    public function test_a_new_author_is_automatically_added()
    {
        $this->post('/books', [
            'title' => 'book 1',
            'author_id' => 'James',
        ]);

        $book = Book::first();
        $author = Author::first();

        $this->assertEquals($author->id, $book->author_id);
        $this->assertCount(1, Author::all());
    }

    private function getData() 
    {
        return [
            'title' => 'book 1',
            'author_id' => 'James',
        ];
    }
}
