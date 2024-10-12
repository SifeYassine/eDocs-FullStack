<?php

namespace App\Http\Controllers\api\categories;

use App\Models\Category;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Validator;

class CategoryController extends Controller
{
    // Create a new category
    public function create(Request $request)
    {
        try {
            $validateCategory = Validator::make($request->all(), [
                'name' => 'required|string|max:255|unique:categories',
                'user_id' => 'nullable|integer|exists:users,id',
            ]);

            if ($validateCategory->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validation error',
                    'errors' => $validateCategory->errors()
                ], 400);
            }

            $category = Category::create([
                'name' => $request->name,
                'user_id' => Auth::id(),
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Category created successfully',
                'category' => $category,
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => $th->getMessage()
            ], 500);
        }
    }

    // Get all categories
    public function index()
    {
        try {
            // Display only the categories created by the current user
            $categories = Category::where('user_id', Auth::id())->get();
            
            return response()->json([
                'status' => true,
                'message' => 'All categories',
                'categories' => $categories
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => $th->getMessage()
            ], 500);
        }
    }

    // Update a category
    public function update(Request $request, $id)
    {
        try {
            // Find only the category created by the current user to update
            $category = Category::where('user_id', Auth::id())->find($id);

            if (!$category) {
                return response()->json([
                    'status' => false,
                    'message' => 'Category not found',
                ], 404);
            }

            $validateCategory = Validator::make($request->all(), [
                'name' => 'required|string|max:255|unique:categories,name,' . $id,
            ]);

            if ($validateCategory->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validation error',
                    'errors' => $validateCategory->errors()
                ], 400);
            }

            $category->update([
                'name' => $request->name,
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Category updated successfully',
                'category' => $category,
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => $th->getMessage()
            ], 500);
        }
    }

    // Delete a category
    public function delete($id)
    {
        try {
            // Find only the category created by the current user to delete
            $category = Category::where('user_id', Auth::id())->find($id);

            if (!$category) {
                return response()->json([
                    'status' => false,
                    'message' => 'Category not found',
                ], 404);
            }

            $category->delete();
            return response()->json([
                'status' => true,
                'message' => 'Category deleted successfully',
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => $th->getMessage()
            ], 500);
        }
    }
}