<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserPermissionController extends Controller
{
    public function index($id)
    {
        $user = User::findOrFail($id);

        $user->load('permissions');
        $permissions = $user->permissions->pluck('name');

        return response()->json([
            'user_id'     => $user->id,
            'permissions' => $permissions,
        ]);
    }

    public function update(Request $request, $id)
    {
 
        // Prevent users from changing their own permissions
        if (Auth::id() == $id) {
            return response()->json([
                'message' => 'You cannot update your own permissions.',
            ], 403);
        }

        $validated = $request->validate([
            'permissions'   => 'required|array',
            'permissions.*' => 'string|exists:permissions,name',
        ]);

        $user = User::findOrFail($id);

        $user->syncPermissions($validated['permissions']);

        return response()->json([
            'message'     => 'Permissions updated successfully.',
            'user_id'     => $user->id,
            'permissions' => $user->getPermissionNames(),
        ]);
    }
}
