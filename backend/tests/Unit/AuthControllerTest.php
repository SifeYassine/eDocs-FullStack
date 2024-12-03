<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;
use App\Models\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\Sanctum;
use PHPUnit\Framework\Attributes\Test;

class AuthControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
    }

    public function test_user_can_register_as_admin_and_user()
    {
        // Perform the POST request to register the first user
        $firstUserResponse = $this->postJson('/api/auth/register', [
            'username' => 'adminuser',
            'email' => 'admin@test.com',
            'password' => 'password123',
        ]);
    
        // Assert the response is successful
        $firstUserResponse->assertStatus(200)
                          ->assertJson([
                              'status' => true,
                              'message' => 'User registered successfully',
                              'user' => [
                                  'username' => $firstUserResponse->json('user.username'),
                                  'email' => $firstUserResponse->json('user.email'),
                              ]
                          ]);
    
        // Assert that the first user was added to the database
        $this->assertDatabaseHas('users', [
            'username' => 'adminuser',
            'email' => 'admin@test.com',
        ]);
    
        // Assert that the first user has the Admin role
        $adminUser = User::with('role')->where('username', 'adminuser')->first();
        $this->assertEquals('Admin', $adminUser->role->name);
    
        // Perform the POST request to register the second user
        $secondUserResponse = $this->postJson('/api/auth/register', [
            'username' => 'regularuser',
            'email' => 'user@test.com',
            'password' => 'password123',
        ]);
    
        // Assert the response is successful
        $secondUserResponse->assertStatus(200)
                           ->assertJson([
                               'status' => true,
                               'message' => 'User registered successfully',
                               'user' => [
                                   'username' => $secondUserResponse->json('user.username'),
                                   'email' => $secondUserResponse->json('user.email'),
                               ]
                           ]);
    
        // Assert that the second user was added to the database
        $this->assertDatabaseHas('users', [
            'username' => 'regularuser',
            'email' => 'user@test.com',
        ]);
    
        // Assert that the second user has the User role
        $regularUser = User::with('role')->where('username', 'regularuser')->first();
        $this->assertEquals('User', $regularUser->role->name);
    }

    public function test_user_can_login()
    {
        // Use the factory to create a user instance
        $user = User::factory()->create([
            'username' => 'testuser',
            'password' => Hash::make('password123')
        ]);

        // Perform the POST request to login the user
        $response = $this->postJson('/api/auth/login', [
            'username' => 'testuser',
            'password' => 'password123'
        ]);

        // Assert the response is successful and contains the expected structure
        $response->assertStatus(200)
                ->assertJson([
                    'status' => true,
                    'message' => 'User logged in successfully',
                    'user' => [
                        'username' => $user->username,
                        'email' => $user->email
                    ]
                ]);

        $this->assertDatabaseHas('users', [
            'username' => $user->username,
            'email' => $user->email
        ]);

        // Assert that the user is authenticated
        $this->assertAuthenticatedAs($user);    
    }

    public function test_user_can_logout()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $response = $this->postJson('/api/auth/logout');

        $response->assertStatus(200)
            ->assertJson([
                'status' => true,
                'message' => 'User logged out successfully'
            ]);
    }
}