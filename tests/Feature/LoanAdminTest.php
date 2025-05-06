<?php

namespace Tests\Feature;

use App\Models\Loan;
use App\Models\User;
use App\Models\Book;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class LoanAdminTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = User::factory()->create(['role' => 'admin']);
    }

    #[Test]
    public function admin_can_view_all_loans()
    {
        $user = User::factory()->create();
        $book = Book::factory()->create();

        Loan::factory()->count(5)->create([
            'user_id' => $user->id,
            'book_id' => $book->id,
            'status' => 'borrowed',
        ]);

        $response = $this->actingAs($this->admin)->getJson('/api/admin/loans');

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'status',
                     'message',
                     'data' => [
                         '*' => ['id', 'user_id', 'book_id', 'borrowed_at', 'returned_at', 'status', 'created_at', 'updated_at']
                     ]
                 ]);
    }

    #[Test]
    public function admin_can_update_loan_status()
    {
        $user = User::factory()->create();
        $book = Book::factory()->create();

        $loan = Loan::factory()->create([
            'user_id' => $user->id,
            'book_id' => $book->id,
            'status' => 'borrowed'
        ]);

        $response = $this->actingAs($this->admin)->putJson("/api/admin/loans/{$loan->id}", [
            'status' => 'returned',
        ]);

        $response->assertStatus(200)
                 ->assertJsonFragment([
                     'message' => 'Loan status updated successfully',
                     'status' => 'success',
                 ])
                 ->assertJsonPath('data.status', 'returned');

        $this->assertDatabaseHas('loans', [
            'id' => $loan->id,
            'status' => 'returned',
        ]);
    }
}
