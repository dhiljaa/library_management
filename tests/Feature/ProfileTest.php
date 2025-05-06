<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProfileTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_view_profile()
    {
        $user = User::factory()->create();

        $token = $user->createToken('auth_token')->plainTextToken;

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
                         ->getJson('/api/profile');

        $response->assertOk()
                 ->assertJson([
                     'status' => 'success',
                     'data' => [
                         'id'    => $user->id,
                         'name'  => $user->name,
                         'email' => $user->email,
                     ]
                 ]);
    }

    public function test_authenticated_user_can_update_profile()
    {
        $user = User::factory()->create([
            'name' => 'Old Name',
            'email' => 'old@example.com',
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        $newData = [
            'name' => 'New Name',
            'email' => 'new@example.com',
        ];

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
                         ->putJson('/api/profile', $newData);

        $response->assertOk()
                 ->assertJson([
                     'status' => 'success',
                     'message' => 'Profile updated successfully',
                     'data' => [
                         'name'  => 'New Name',
                         'email' => 'new@example.com',
                     ]
                 ]);

        $this->assertDatabaseHas('users', [
            'id'    => $user->id,
            'name'  => 'New Name',
            'email' => 'new@example.com',
        ]);
    }
}
