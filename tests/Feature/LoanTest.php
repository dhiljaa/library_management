<?php

namespace Tests\Feature;

use App\Models\Book;
use App\Models\Loan;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LoanTest extends TestCase
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

    public function test_user_can_view_their_loans()
    {
        $book = Book::factory()->create();
        Loan::factory()->create([
            'user_id' => $this->user->id,
            'book_id' => $book->id,
            'returned_at' => now(), // history
        ]);

        $response = $this->withToken($this->token)->getJson('/api/loans/history');

        $response->assertOk()
                 ->assertJsonStructure([
                     'status',
                     'data' => [
                         ['id', 'book_id', 'user_id', 'borrowed_at', 'returned_at']
                     ]
                 ]);
    }

    public function test_user_can_borrow_a_book()
    {
        $book = Book::factory()->create();

        $response = $this->withToken($this->token)->postJson('/api/loans', [
            'book_id' => $book->id,
        ]);

        $response->assertCreated()
                 ->assertJsonStructure([
                     'status',
                     'message',
                     'data' => [
                         'id',
                         'book_id',
                         'user_id',
                         'borrowed_at',
                         'returned_at'
                     ]
                 ]);
    }

    public function test_user_cannot_borrow_same_book_twice()
    {
        $book = Book::factory()->create();

        Loan::factory()->create([
            'user_id' => $this->user->id,
            'book_id' => $book->id,
            'returned_at' => null,
        ]);

        $response = $this->withToken($this->token)->postJson('/api/loans', [
            'book_id' => $book->id,
        ]);

        $response->assertStatus(422)
                 ->assertJson([
                     'status' => 'error',
                     'message' => 'Book already borrowed and not yet returned',
                 ]);
    }

    public function test_user_can_return_a_book()
    {
        $loan = Loan::factory()->create([
            'user_id' => $this->user->id,
            'returned_at' => null,
        ]);

        $response = $this->withToken($this->token)->putJson("/api/loans/{$loan->id}/return");

        $response->assertOk()
                 ->assertJson([
                     'status' => 'success',
                     'message' => 'Book returned successfully'
                 ]);
    }
}
