<?php

namespace Tests\Feature;

use App\Models\Book;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BookTest extends TestCase
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

    public function test_user_can_get_all_books()
    {
        Book::factory()->count(3)->create();

        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
                         ->getJson('/api/books');

        $response->assertOk()
                 ->assertJsonStructure([
                     'status',
                     'data' => [['id', 'title', 'author', 'category', 'description']]
                 ]);
    }

    public function test_user_can_get_book_details()
    {
        $book = Book::factory()->create();

        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
                         ->getJson("/api/books/{$book->id}");

        $response->assertOk()
                 ->assertJsonFragment([
                     'id' => $book->id,
                     'title' => $book->title,
                 ]);
    }

    public function test_user_can_get_books_by_category()
    {
        Book::factory()->create(['category' => 'Novel']);
        Book::factory()->create(['category' => 'Komik']);

        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
                         ->getJson('/api/books/category/Novel');

        $response->assertOk()
                 ->assertJsonFragment(['category' => 'Novel']);
    }

    public function test_user_can_get_top_books()
    {
        Book::factory()->count(5)->create();

        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
                         ->getJson('/api/books/top');

        $response->assertOk()
                 ->assertJsonStructure([
                     'status',
                     'data'
                 ]);
    }
}
