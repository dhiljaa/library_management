<?php

namespace Tests\Feature;

use App\Models\Book;
use App\Models\Loan;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LoanStaffTest extends TestCase
{
    use RefreshDatabase;

    protected $staff;

    protected function setUp(): void
    {
        parent::setUp();

        $this->staff = User::factory()->create(['role' => 'staff']);
    }

    public function test_staff_can_view_all_loans()
    {
        $user = User::factory()->create();
        $book = Book::factory()->create();
        Loan::factory()->create([
            'user_id' => $user->id,
            'book_id' => $book->id,
        ]);

        $response = $this->actingAs($this->staff)->getJson('/api/staff/loans');

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'data' => [
                         '*' => ['id', 'user_id', 'book_id', 'status', 'borrowed_at', 'returned_at', 'created_at', 'updated_at']
                     ]
                 ]);
    }

    public function test_staff_can_update_loan_status()
    {
        $user = User::factory()->create();
        $book = Book::factory()->create();
        $loan = Loan::factory()->create([
            'user_id' => $user->id,
            'book_id' => $book->id,
            'status' => 'borrowed',
        ]);

        $response = $this->actingAs($this->staff)->putJson("/api/staff/loans/{$loan->id}", [
            'status' => 'returned',
        ]);

        $response->assertStatus(200)
                 ->assertJson([
                     'message' => 'Loan status updated by staff',
                     'data' => [
                         'id' => $loan->id,
                         'status' => 'returned',
                     ],
                 ]);
    }
}
