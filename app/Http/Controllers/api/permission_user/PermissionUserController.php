<?php

namespace App\Http\Controllers\api\permission_user;

use App\Models\Permission;
use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;

class PermissionUserController extends Controller
{
    // Assign permission to user
    public function assignPermissionToUser(Request $request, $userId, $permissionId)
    {
        try {
            // Validate that the user_id and permission_id exist
            $validator = Validator::make(
                ['user_id' => $userId, 'permission_id' => $permissionId],
                [
                    'user_id' => 'required|integer|exists:users,id',
                    'permission_id' => 'required|integer|exists:permissions,id',
                ]
            );

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validation error',
                    'errors' => $validator->errors(),
                ], 400);
            }

            // Assign the permission to the user
            $user = User::find($userId);
            $user->permissions()->attach($permissionId);

            return response()->json([
                'status' => true,
                'message' => 'Permission assigned to user successfully',
            ], 200);

        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage(),
            ], 500);
        }
    }

    // Get permissions assigned to user
    public function getPermissionsAssignedToUser($userId)
    {
        try {
            // Validate that the user_id
            $validator = Validator::make(
                ['user_id' => $userId],
                ['user_id' => 'required|integer|exists:users,id',]
            );

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validation error',
                    'errors' => $validator->errors(),
                ], 400);
            }
            $user = User::find($userId);
            $permissions = $user->permissions;
            return response()->json([
                'status' => true,
                'message' => 'Permissions fetched successfully',
                'permissions' => $permissions,
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => $th->getMessage()
            ], 500);
        }
    }

    // Revoke permission from user
    public function revokePermissionFromUser(Request $request, $userId, $permissionId)
    {
        try {
            // Validate that the user_id and permission_id exist
            $validator = Validator::make(
                ['user_id' => $userId, 'permission_id' => $permissionId],
                [
                    'user_id' => 'required|integer|exists:users,id',
                    'permission_id' => 'required|integer|exists:permissions,id',
                ]
            );

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validation error',
                    'errors' => $validator->errors(),
                ], 400);
            }

            // Revoke the permission from the user
            $user = User::find($userId);
            $user->permissions()->detach($permissionId);

            return response()->json([
                'status' => true,
                'message' => 'Permission revoked from user successfully',
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => $th->getMessage()
            ], 500);
        }
    }

}