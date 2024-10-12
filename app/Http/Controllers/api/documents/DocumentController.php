<?php

namespace App\Http\Controllers\api\documents;

use App\Models\Document;
use App\Models\Category;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Validator;


class DocumentController extends Controller
{
    // Create a new Document
    public function create(Request $request)
    {
        try {
            $validateDocument = Validator::make($request->all(), [
                'title' => 'nullable|string|max:255',
                'format' => 'nullable|string|max:255',
                'path_url' => 'required|file|max:10240',
                'category_id' => 'required|integer|exists:categories,id',
                'user_id' => 'nullable|integer|exists:users,id',
            ]);

            // Check if validation failed
            if ($validateDocument->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validation error',
                    'errors' => $validateDocument->errors()
                ], 400);
            }

            // File type validation
            $uploadedFile = $request->file('path_url');
            $fileExtension = $uploadedFile->getClientOriginalExtension();
            $fileName = $uploadedFile->getClientOriginalName();
            $fileTitle = str_replace('.' . $fileExtension, '', $fileName);
            $allowedExtensions = ['pdf', 'docx', 'xlsx', 'pptx', 'txt'];

            if (!in_array($fileExtension, $allowedExtensions)) {
                return response()->json([
                    'status' => false,
                    'message' => 'Invalid file type. the file must be file of type: ' . implode(', ', $allowedExtensions),
                ], 400);
            }

            // Check if the provided category exists (created by the current user)
            $category = Category::where('user_id', Auth::id())->find($request->category_id);

            if (!$category) {
                return response()->json([
                    'status' => false,
                    'message' => 'Category not found',
                ], 404);
            }

            // Handle document path in test environment
            if (app()->environment('testing')) {
                $filePath = 'test.pdf';
            } else {
                // Handle document path in development environment
                $folder = 'public/documents';
                $filePath = $uploadedFile->storeAs($folder, $fileName);
                $filePath = Storage::url($filePath);
            }

            $document = Document::create([
                'title' => $fileTitle,
                'format' => $fileExtension,
                'path_url' => $filePath,
                'category_id' => $category->id,
                'user_id' => Auth::id(),
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Document created successfully',
                'Document' => $document
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => $th->getMessage()
            ], 500);
        }
    }

    // Get all documents
    public function index()
    {
        try {
            // Display only the documents created by the current user
            $documents = Document::where('user_id', Auth::id())->get();

            return response()->json([
                'status' => true,
                'message' => 'All documents',
                'documents' => $documents
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => $th->getMessage()
            ], 500);
        }
    }

    // Update a Document
    public function update(Request $request, $id)
    {
        try {
            // Find only the document created by the current user to update
            $document = Document::where('user_id', Auth::id())->find($id);
            
            if (!$document) {
                return response()->json([
                    'status' => false,
                    'message' => 'Document not found'
                ], 404);
            }
    
            // Validate only the fields that are being updated
            $validateDocument = Validator::make($request->all(), [
                'title' => 'required|string|max:255',
                'category_id' => 'nullable|integer|exists:categories,id',
            ]);
    
            if ($validateDocument->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validation error',
                    'errors' => $validateDocument->errors()
                ], 400);
            }
    
            $document->update([
                'title' => $request->title,
                'category_id' => $request->category_id
            ]);
    
            return response()->json([
                'status' => true,
                'message' => 'Document updated successfully',
                'Document' => $document
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => $th->getMessage()
            ], 500);
        }
    }    
    
    // Delete a Document
    public function delete($id)
    {
        try {
            // Find only the document created by the current user to delete
            $document = Document::where('user_id', Auth::id())->find($id);

            if (!$document) {
                return response()->json([
                    'status' => false,
                    'message' => 'Document not found'
                ], 404);
            }

            $document->delete();
            return response()->json([
                'status' => true,
                'message' => 'Document deleted successfully',
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => $th->getMessage()
            ], 500);
        }
    }
}