<?php

namespace Tests\Feature;

use App\Models\Book;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class AdminBookTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = User::factory()->create([
            'role' => 'admin',
        ]);
    }

    #[Test]
    public function admin_can_create_a_book(): void
    {
        $response = $this->actingAs($this->admin, 'sanctum')->postJson('/api/admin/books', [
            'title' => 'Buku Baru',
            'author' => 'Penulis Hebat',
            'description' => 'Deskripsi buku yang menarik',
            'category' => 'Novel',
            'published_year' => 2023,
            'quantity' => 10, // ✅ Ditambahkan field quantity
        ]);

        $response->assertStatus(201);
        $this->assertDatabaseHas('books', ['title' => 'Buku Baru']);
    }

    #[Test]
    public function admin_can_update_a_book(): void
    {
        $book = Book::factory()->create();

        $response = $this->actingAs($this->admin, 'sanctum')->putJson("/api/admin/books/{$book->id}", [
            'title' => 'Judul Diperbarui',
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('books', ['id' => $book->id, 'title' => 'Judul Diperbarui']);
    }

    #[Test]
    public function admin_can_delete_a_book(): void
    {
        $book = Book::factory()->create();

        $response = $this->actingAs($this->admin, 'sanctum')->deleteJson("/api/admin/books/{$book->id}");

        $response->assertStatus(200);
        $this->assertDatabaseMissing('books', ['id' => $book->id]);
    }
}
