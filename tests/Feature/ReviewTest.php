<?php

namespace Tests\Feature;

use App\Models\Book;
use App\Models\Review;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReviewTest extends TestCase
{
    use RefreshDatabase;

    protected $user;
    protected $token;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        $this->token = $this->user->createToken('test-token')->plainTextToken;
    }

    public function test_user_can_submit_a_review()
    {
        $book = Book::factory()->create();

        $response = $this->withToken($this->token)->postJson("/api/books/{$book->id}/reviews", [
            'book_id' => $book->id, // penting untuk validasi di controller
            'rating' => 4,
            'comment' => 'Great book!',
        ]);

        $response->assertCreated()
                 ->assertJsonStructure([
                     'status',
                     'message',
                     'data' => [
                         'id',
                         'user_id',
                         'book_id',
                         'rating',
                         'comment',
                         'created_at',
                         'updated_at',
                     ],
                 ])
                 ->assertJsonFragment([
                     'status' => 'success',
                     'message' => 'Review submitted successfully',
                 ]);
    }

    public function test_user_cannot_review_same_book_twice()
    {
        $book = Book::factory()->create();

        Review::factory()->create([
            'book_id' => $book->id,
            'user_id' => $this->user->id,
        ]);

        $response = $this->withToken($this->token)->postJson("/api/books/{$book->id}/reviews", [
            'book_id' => $book->id,
            'rating' => 5,
            'comment' => 'Second review attempt',
        ]);

        $response->assertStatus(422)
                 ->assertJsonFragment([
                     'status' => 'error',
                     'message' => 'You have already reviewed this book.',
                 ]);
    }

    public function test_user_can_update_their_review()
    {
        $book = Book::factory()->create();
        $review = Review::factory()->create([
            'book_id' => $book->id,
            'user_id' => $this->user->id,
            'rating' => 3,
            'comment' => 'Old comment'
        ]);

        $response = $this->withToken($this->token)->putJson("/api/reviews/{$review->id}", [
            'rating' => 5,
            'comment' => 'Updated comment'
        ]);

        $response->assertOk()
                 ->assertJsonFragment([
                     'status' => 'success',
                     'message' => 'Review updated successfully',
                 ]);
    }

    public function test_user_can_delete_their_review()
    {
        $review = Review::factory()->create([
            'user_id' => $this->user->id,
        ]);

        $response = $this->withToken($this->token)->deleteJson("/api/reviews/{$review->id}");

        $response->assertOk()
                 ->assertJsonFragment([
                     'status' => 'success',
                     'message' => 'Review deleted successfully',
                 ]);
    }
}
