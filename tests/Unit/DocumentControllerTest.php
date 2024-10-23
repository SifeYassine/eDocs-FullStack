<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;
use App\Models\Category;
use App\Models\Document;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Laravel\Sanctum\Sanctum;
use PHPUnit\Framework\Attributes\Test;

class DocumentControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $user;
    protected $category;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->user = User::factory()->create();

        // Create a category for the user using the factory
        $this->category = Category::factory()->create([
            'user_id' => $this->user->id
        ]);
    }

    public function test_user_can_create_document()
    {
        // Act as the authenticated user
        Sanctum::actingAs($this->user);
    
        // Use the factory to create a document instance
        $document = Document::factory()->make([
            'user_id' => $this->user->id,
            'category_id' => $this->category->id
        ]);
        
        // Create a fake file based on the document format and title
        $file = UploadedFile::fake()->create($document->title . '.' . $document->format);
    
        // Perform the POST request to create the document
        $response = $this->postJson('/api/documents/create', [
            'title' => $document->title,
            'format' => $document->format,
            'path_url' => $file,
            'category_id' => $this->category->id,
            'user_id' => $this->user->id
        ]);
    
        // Assert the response is successful
        $response->assertStatus(200)
                 ->assertJson([
                     'status' => true,
                     'message' => 'Document created successfully',
                     'document' => [
                         'title' => $document->title,
                         'format' => $document->format,
                         'path_url' => "/storage/documents/" . $document->title . '.' . $document->format,
                         'user_id' => $this->user->id,
                         'category_id' => $this->category->id
                     ]
                 ]);
    
        // Assert the document was added to the database
        $this->assertDatabaseHas('documents', [
            'title' => $document->title,
            'format' => $document->format,
            'path_url' => "/storage/documents/" . $document->title . '.' . $document->format,
            'user_id' => $this->user->id,
            'category_id' => $this->category->id,
        ]);
    
        // Assert the file was uploaded to storage
        $this->assertFileExists(storage_path("app/public/documents/{$document->title}.$document->format"));
    }
          
    public function test_user_can_get_their_documents()
    {
        Sanctum::actingAs($this->user);
    
        // Create documents for the current user
        $userDocuments = Document::factory()->count(3)->create([
            'user_id' => $this->user->id,
            'category_id' => $this->category->id
        ]);
    
        // Create documents for another user
        $otherUser = User::factory()->create();
        Document::factory()->count(2)->create([
            'user_id' => $otherUser->id
        ]);
    
        $response = $this->getJson('/api/documents/index');
    
        $response->assertStatus(200)
                ->assertJson([
                    'status' => true,
                    'message' => 'All documents',
                ]);
    
        // Assert that only the authenticated user's documents are returned
        $this->assertCount(3, $response->json('documents'));
    
        // Check that all documents belong to the authenticated user
        $documents = $response->json('documents');
        foreach ($documents as $doc) {
            $this->assertEquals($this->user->id, $doc['user_id']);
        }
    }

    public function test_user_can_update_their_document()
    {
        Sanctum::actingAs($this->user);

        $document = Document::factory()->create([
            'user_id' => $this->user->id,
            'category_id' => $this->category->id
        ]);

        $newCategory = Category::factory()->create([
            'user_id' => $this->user->id
        ]);

        $updateDocument = [
            'title' => 'Updated Document Title',
            'category_id' => $newCategory->id
        ];

        $response = $this->putJson("/api/documents/update/{$document->id}", $updateDocument);

        $response->assertStatus(200)
                ->assertJson([
                    'status' => true,
                    'message' => 'Document updated successfully'
                ]);

        $this->assertDatabaseHas('documents', [
            'id' => $document->id,
            'title' => 'Updated Document Title',
            'category_id' => $newCategory->id
        ]);
    }

    public function test_user_can_delete_their_document()
    {
        Sanctum::actingAs($this->user);

        $document = Document::factory()->create([
            'user_id' => $this->user->id
        ]);

        $response = $this->deleteJson("/api/documents/delete/{$document->id}");

        $response->assertStatus(200)
                ->assertJson([
                    'status' => true,
                    'message' => 'Document deleted successfully'
                ]);

        $this->assertDatabaseMissing('documents', [
            'id' => $document->id
        ]);
    }
}
