<?php

namespace Tests\Unit;

use App\Models\Book;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BookTest extends TestCase
{
    use RefreshDatabase;
    
    public function test_an_author_id_is_recorded(): void
    {
        Book::Create([
            'title' => 'title 33',
            'author_id' => 1,
        ]);

        $this->assertCount(1, Book::all());
    }
}
