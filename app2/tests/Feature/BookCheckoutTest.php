<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Book;
use App\Models\User;
use App\Models\Author;
use App\Models\Reservation;
use Exception;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class BookCheckoutTest extends TestCase
{
    use RefreshDatabase;

    public function test_a_book_can_be_checkout_by_a_signed_user()
    {
        $book = Book::factory()->create();

        $this->actingAs($user = User::factory()->create())
            ->post("/checkout/$book->id");

        $this->assertCount(1, Reservation::all());
        $this->assertEquals($user->id, Reservation::first()->user_id);
        $this->assertEquals($book->id, Reservation::first()->book_id);
        $this->assertEquals(now(), Reservation::first()->checked_out_at);
    }

    public function test_only_signed_in_users_can_checkout_a_book()
    {
        $book = Book::factory()->create();

        $this->post("/checkout/$book->id")
            ->assertRedirect('/login');

        $this->assertCount(0, Reservation::all());
    }

    public function test_only_signed_in_users_can_checkin_a_book()
    {
        $book = Book::factory()->create();

        $this->post("/checkin/$book->id")
            ->assertRedirect('/login');

        $this->assertCount(0, Reservation::all());
    }

    public function test_only_real_books_can_be_checked_out()
    {
        $this->actingAs(User::factory()->create())
            ->post("/checkout/122")
            ->assertStatus(404);

        $this->assertCount(0, Reservation::all());
    }

    public function test_a_book_can_be_checkin_by_a_signed_user()
    {
        $book = Book::factory()->create();
        $user = User::factory()->create();

        $this->actingAs($user)->post("/checkout/$book->id");
        $this->actingAs($user)->post("/checkin/$book->id");

        $this->assertCount(1, Reservation::all());
        $this->assertEquals($user->id, Reservation::first()->user_id);
        $this->assertEquals($book->id, Reservation::first()->book_id);
        $this->assertEquals(now(), Reservation::first()->checked_in_at);
    }
}
