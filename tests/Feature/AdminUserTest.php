<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class AdminUserTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        // Buat user admin
        $this->admin = User::factory()->create([
            'role' => 'admin',
        ]);
    }

    #[Test]
    public function admin_can_view_all_users(): void
    {
        // Buat beberapa user biasa
        User::factory()->count(5)->create(['role' => 'user']);

        $response = $this->actingAs($this->admin)->getJson('/api/admin/users');

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'message',
                     'data' => [
                         '*' => ['id', 'name', 'email', 'role']
                     ]
                 ]);
    }

    #[Test]
    public function admin_can_update_user_role(): void
    {
        $user = User::factory()->create(['role' => 'user']);

        $response = $this->actingAs($this->admin)->putJson("/api/admin/users/{$user->id}/role", [
            'role' => 'staff'
        ]);

        $response->assertStatus(200)
                 ->assertJsonFragment([
                     'message' => 'User role updated successfully',
                     'role' => 'staff'
                 ]);

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'role' => 'staff'
        ]);
    }

    #[Test]
    public function admin_can_delete_a_user(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($this->admin)->deleteJson("/api/admin/users/{$user->id}");

        $response->assertStatus(200)
                 ->assertJsonFragment([
                     'message' => 'User deleted successfully'
                 ]);

        $this->assertDatabaseMissing('users', ['id' => $user->id]);
    }
}
