<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PermissionMiddleware
{   
    public function handle(Request $request, Closure $next, $permission)
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json([
                'message' => 'Unauthenticated.'
            ], 401);
        }

        // Check if user is an Admin or has the required permission
        if ($user->role->name === 'Admin' || $user->permissions->contains('label', $permission)) {
            return $next($request);
        }

        return response()->json([
            'message' => 'You do not have permission to perform this action'
        ], 403);
    }
}