<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;
use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use PHPUnit\Framework\Attributes\Test;

class CategoryControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();

        // Create a user using the factory
        $this->user = User::factory()->create();
    }

    public function test_user_can_create_category()
    {
        // Act as the authenticated user
        Sanctum::actingAs($this->user);
    
        // Use the factory to create a category instance as created by the authenticated user
        $category = Category::factory()->make([
            'user_id' => $this->user->id,
        ]);
    
        // Perform the POST request to create the category
        $response = $this->postJson('/api/categories/create', [
            'name' => $category->name,
            'user_id' => $category->user_id
        ]);
    
        // Assert the response is successful
        $response->assertStatus(200)
                ->assertJson([
                    'status' => true,
                    'message' => 'Category created successfully',
                    'category' => [
                        'name' => $category->name,
                        'user_id' => $category->user_id
                    ]
                ]);
    
        // Assert that the category was added to the database
        $this->assertDatabaseHas('categories', [
            'name' => $category->name,
            'user_id' => $category->user_id
        ]);
    }

    public function test_user_can_get_their_categories()
    {
        Sanctum::actingAs($this->user);

        // Create some categories for the authenticated user
        $userCategories = Category::factory()->count(3)->create([
            'user_id' => $this->user->id
        ]);

        // Create categories for another user
        $otherUser = User::factory()->create();
        Category::factory()->count(2)->create([
            'user_id' => $otherUser->id
        ]);

        $response = $this->getJson('/api/categories/index');

        $response->assertStatus(200)
                ->assertJson([
                    'status' => true,
                    'message' => 'All categories',
                    'categories' => collect($userCategories)->toArray()
                ]);

        // Assert that only the user's categories are returned
        $this->assertEquals(3, count($response->json('categories')));

        // Check that all categories belong to the authenticated user
        $categories = $response->json('categories');
        foreach ($categories as $categ) {
            $this->assertEquals($this->user->id, $categ['user_id']);
        }
    }

    public function test_user_can_update_their_category()
    {
        Sanctum::actingAs($this->user);

        $category = Category::factory()->create([
            'user_id' => $this->user->id
        ]);

        $updateCategory = [
            'name' => 'Updated Category Name'
        ];

        $response = $this->putJson("/api/categories/update/{$category->id}", $updateCategory);

        $response->assertStatus(200)
                ->assertJson([
                    'status' => true,
                    'message' => 'Category updated successfully'
                ]);

        $this->assertDatabaseHas('categories', [
            'id' => $category->id,
            'name' => 'Updated Category Name',
            'user_id' => $this->user->id
        ]);
    }

    public function test_user_can_delete_their_category()
    {
        Sanctum::actingAs($this->user);

        $category = Category::factory()->create([
            'user_id' => $this->user->id
        ]);

        $response = $this->deleteJson("/api/categories/delete/{$category->id}");

        $response->assertStatus(200)
                ->assertJson([
                    'status' => true,
                    'message' => 'Category deleted successfully'
                ]);

        $this->assertDatabaseMissing('categories', [
            'id' => $category->id
        ]);
    }
}