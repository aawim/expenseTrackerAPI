<?php
namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\UpdateUserPermissionRequest;

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

    public function update(UpdateUserPermissionRequest $request, $id)
    {
 
        // Prevent users from changing their own permissions
        if (Auth::id() == $id) {
            return response()->json([
                'message' => 'You cannot update your own permissions.',
            ], 403);
        }
        $user = User::findOrFail($id);

        $user->syncPermissions($request['permissions']);

        return response()->json([
            'message'     => 'Permissions updated successfully.',
            'user_id'     => $user->id,
            'permissions' => $user->getPermissionNames(),
        ]);
    }
}
