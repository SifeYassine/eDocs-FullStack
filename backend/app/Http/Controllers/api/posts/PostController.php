<?php

namespace App\Http\Controllers\api\posts;

use App\Models\Post;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Validator;

class PostController extends Controller
{
    public function create(Request $request)
    {
        try {
            $validatePost = Validator::make($request->all(), [
                'title' => 'required|string|max:255',
                'description' => 'required|string|max:255',
                'is_deleted' => 'sometimes|boolean|default:false',
            ]);

            if ($validatePost->fails()) {
                return response()->json([
                    'status' => 400,
                    'message' => 'Validation error',
                    'errors' => $validatePost->errors()
                ], 400);
            }

            $post = Post::create([
                'title' => $request->title,
                'description' => $request->description,
                'is_deleted' => false
            ]);

            return response()->json([
                'status' => 400,
                'message' => 'Post created successfully',
                'post' => $post,
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 500,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    public function index()
    {
        try {
            $posts = Post::where('is_deleted', false)->get();
            
            return response()->json([
                'status' => 200,
                'message' => 'All posts',
                'posts' => $posts
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 500,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    public function delete($id)
    {
        try {
            $post = Post::where('id', $id)->first();

            if (!$post) {
                return response()->json([
                    'status' => 404,
                    'message' => 'Post not found',
                ], 404);
            }

            $post->update([
                'is_deleted' => true
            ]);

            return response()->json([
                'status' => 200,
                'message' => 'Post deleted successfully',
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 500,
                'message' => $th->getMessage()
            ], 500);
        }
    }
}