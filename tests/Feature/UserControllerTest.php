<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Facades\Validator;

class UserControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function returnPaginated(): void
    {
        $user = User::factory()->create();
        User::factory()->count(20)->create();

        $response = $this->actingAs($user)->getJson('/api/users?page=1&per_page=15');

        $response->assertStatus(200);
        
        $response->assertJsonStructure(['data']);

        $response->assertJsonCount(15, 'data');
    }

    /** @test */
    public function createUser(): void
    {
        $this->mockCaptchaValidation();

        $user = User::factory()->create();

        $userData = [
            'name' => 'John Jones',
            'email' => 'john@jones.com',
            'password' => '123456',
            'password_confirmation' => '123456',
            'is_active' => true, 
            'g-recaptcha-response' => 'fake-recaptcha-token',
        ];

        $response = $this->actingAs($user)->postJson('/api/users', $userData);
        
        $response->assertStatus(201);
        $this->assertDatabaseHas('users', ['email' => 'john@jones.com']);
        $response->assertJsonFragment(['name' => 'John Jones']);
    }

    /** @test */
    public function returnUser(): void
    {
        $authenticatedUser = User::factory()->create();
        $userToShow = User::factory()->create();

        $response = $this->actingAs($authenticatedUser)->getJson("/api/users/{$userToShow->id}");

        $response->assertStatus(200);
        
        $response->assertJson([
            'id' => $userToShow->id,
            'name' => $userToShow->name,
            'email' => $userToShow->email,
        ]);
    }

    /** @test */
    public function updateUser(): void
    {
        $user = User::factory()->create();
        $updateData = ['name' => 'John Smith', 'active' => false];

        $response = $this->actingAs($user)->putJson("/api/users/{$user->id}", $updateData);

        $response->assertStatus(200);
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => 'John Smith',
        ]);
        $response->assertJsonFragment(['name' => 'John Smith']);
    }

    /** @test */
    public function deleteUser(): void
    {
        $authenticatedUser = User::factory()->create();
        $userToDelete = User::factory()->create();

        $response = $this->actingAs($authenticatedUser)->deleteJson("/api/users/{$userToDelete->id}");

        $response->assertStatus(204);
        $this->assertDatabaseMissing('users', ['id' => $userToDelete->id]);
    }

    /** @test */
    public function deleteMultipleUsers(): void
    {
        $authenticatedUser = User::factory()->create();
        $usersToDelete = User::factory()->count(3)->create();
        $idsToDelete = $usersToDelete->pluck('id')->toArray();
        $response = $this->actingAs($authenticatedUser)->postJson('/api/users/bulk-delete', ['ids' => $idsToDelete]);

        $response->assertStatus(204);
        foreach ($idsToDelete as $id) 
        {
            $this->assertDatabaseMissing('users', ['id' => $id]);
        }
    }

        protected function mockCaptchaValidation()
    {
        Validator::extend('captcha', function ($attribute, $value, $parameters, $validator) {
            return true; 
        });
    }
}
