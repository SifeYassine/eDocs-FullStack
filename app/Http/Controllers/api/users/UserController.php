<?php

namespace App\Http\Controllers\api\users;

use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Validator;

class UserController extends Controller
{
    // Get all users
    public function index()
    {
        try {
            $users = User::all();
            
            return response()->json([
                'status' => true,
                'message' => 'All users',
                'users' => $users
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => $th->getMessage()
            ], 500);
        }
    }

    // Show logged user profile
    public function getMyProfile()
    {
        try {
            $user = Auth::user();
            $token = request()->bearerToken();

            return response()->json([
                'status' => true,
                'message' => 'Current Logged In User',
                'user' => $user,
                'access_token' => $token,
                'token_type' => 'Bearer',
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => $th->getMessage()
            ], 500);
        }
    }

    // Edit logged user profile
    public function editMyProfile(Request $request) {
        try {
            $user = Auth::user();
            $id = $user->id;
    
            $validateUser = Validator::make($request->all(), [
                'username' => 'nullable|string|max:255|unique:users,username,' . $id,
                'email' => 'nullable|string|email|max:255|unique:users,email,' . $id,
                'password' => 'nullable|string|min:6',
            ]);
    
            if ($validateUser->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validation error',
                    'errors' => $validateUser->errors()
                ], 400);
            }
    
            // Update only if provided
            $user->update([
                'username' => $request->username ?? $user->username,
                'email' => $request->email ?? $user->email,
                'password' => $request->password ? Hash::make($request->password) : $user->password,
            ]);
    
            return response()->json([
                'status' => true,
                'message' => 'Profile updated successfully',
                'user' => $user
            ], 200);
    
        } catch (\Throwable $th) {
            return response()->json([
                'message' => $th->getMessage()
            ], 500);
        }
    }    

    // Update a user
    public function update(Request $request, $id)
    {
        try {
            $user = User::find($id);

            if (!$user) {
                return response()->json([
                    'status' => false,
                    'message' => 'User not found',
                ], 404);
            }

            $validateUser = Validator::make($request->all(), [
                'role_id' => 'required|integer|exists:roles,id',
            ]);

            if ($validateUser->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validation error',
                    'errors' => $validateUser->errors()
                ], 400);
            }

            $user->update([
                'role_id' => $request->role_id,
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Role Assigned successfully',
                'user' => $user,
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => $th->getMessage()
            ], 500);
        }
    }

    // Delete a user
    public function delete($id)
    {
        try {
            $user = User::find($id);

            if (!$user) {
                return response()->json([
                    'status' => false,
                    'message' => 'User not found',
                ], 404);
            }

            $user->delete();
            return response()->json([
                'status' => true,
                'message' => 'User deleted successfully',
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => $th->getMessage()
            ], 500);
        }
    }
}