<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Book;
use Illuminate\Foundation\Testing\RefreshDatabase;

class BookTest extends TestCase
{
    use RefreshDatabase;

    public function test_an_author_is_recorded()
    {
        Book::create([
            'title' => 'book from author',
            'author_id' => 1,
        ]);

        $this->assertCount(1, Book::all());
    }
}
