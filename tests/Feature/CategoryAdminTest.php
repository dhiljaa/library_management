<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class CategoryAdminTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = User::factory()->create(['role' => 'admin']);
    }

    #[Test]
    public function admin_can_list_categories()
    {
        Category::factory()->count(3)->create();

        $response = $this->actingAs($this->admin)->getJson('/api/admin/categories');

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'data' => [
                         '*' => ['id', 'name', 'created_at', 'updated_at']
                     ]
                 ]);
    }

    #[Test]
    public function admin_can_create_category()
    {
        $response = $this->actingAs($this->admin)->postJson('/api/admin/categories', [
            'name' => 'Fiksi'
        ]);

        $response->assertStatus(201)
                 ->assertJsonFragment(['name' => 'Fiksi']);

        $this->assertDatabaseHas('categories', ['name' => 'Fiksi']);
    }

    #[Test]
    public function admin_can_update_category()
    {
        $category = Category::factory()->create(['name' => 'Non-Fiksi']);

        $response = $this->actingAs($this->admin)->putJson("/api/admin/categories/{$category->id}", [
            'name' => 'Ilmiah'
        ]);

        $response->assertStatus(200)
                 ->assertJsonFragment(['name' => 'Ilmiah']);

        $this->assertDatabaseHas('categories', ['id' => $category->id, 'name' => 'Ilmiah']);
    }

    #[Test]
    public function admin_can_delete_category()
    {
        $category = Category::factory()->create();

        $response = $this->actingAs($this->admin)->deleteJson("/api/admin/categories/{$category->id}");

        $response->assertStatus(200)
                 ->assertJsonFragment(['message' => 'Category deleted successfully']);

        $this->assertDatabaseMissing('categories', ['id' => $category->id]);
    }
}
