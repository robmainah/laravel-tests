<?php

namespace Tests\Unit;

use App\Models\Author;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthorTest extends TestCase
{
    use RefreshDatabase;
    
    public function test_only_a_name_is_required_to_create_an_author(): void
    {
        Author::firstOrCreate([
            'name' => 'james',
        ]);

        $this->assertCount(1, Author::all());
    }
}
