<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function index(Request $request)
    {

        $user = $request->user(); // Get the authenticated user

        //   if (! $user->hasRole('Admin')) {
        // return response()->json(['message' => 'Forbidden. Admins only.'], 403);
        // }

        return UserResource::collection(User::with('role')->paginate());

    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'     => 'required',
            'email'    => 'required|email|unique:users',
            'password' => 'required|min:6',
           // 'role_id'  => 'nullable|exists:roles,id',
        ]);

        $data['password'] = bcrypt($data['password']);

        $user = User::create($data);
        return new UserResource($user->load('role'));
    }

    public function update(Request $request, $id)
    {
        //   // Validate basic user data
        // $data = $request->validate([
        //     'name' => 'sometimes|string|max:255',
        //     'email' => 'sometimes|email',
        //     'role_id' => 'nullable|exists:roles,id'
        // ]);

        // // Update user attributes
        // $user = User::findOrFail($id);
        // $user->update([
        //     'name' => $data['name'],
        //     'email' => $data['email']
        // ]);

        // // Sync role if provided
        // if ($request->has('role_id')) {
        //     $role = Role::find($request->role_id);
        //     $user->syncRoles($role); // This replaces all existing roles with the new one
        // }

        // // Return user with loaded role relationship
        // return new UserResource($user->load('roles'));

        // Prevent users from changing their own roles
        if (auth()->id() == $id && $request->has('role_id')) {
            return response()->json([
                'message' => 'You cannot change your own role.',
            ], 403);
        }

        // Validate user data
        $data = $request->validate([
            'name'    => 'sometimes|string|max:255',
            'email'   => 'sometimes|email',
            //'role_id' => 'nullable|exists:roles,id',
        ]);

        $user = User::findOrFail($id);

        // Update name/email if present
        if (isset($data['name'])) {
            $user->name = $data['name'];
        }

        if (isset($data['email'])) {
            $user->email = $data['email'];
        }

        $user->save();

        // Sync role only if provided and not the current user
        if ($request->has('role_id') && auth()->id() !== $user->id) {
            $role = Role::find($request->role_id);
            if ($role) {
                $user->syncRoles($role);
            }
        }

        return new UserResource($user->load('roles', 'permissions'));
    }


    public function getUserPermissions($userId)
    {
        $user = User::with('permissions', 'roles.permissions')->find($userId);
        


        if (! $user) {
            return response()->json([
                'message' => 'User not found',
            ], 404);
        }
        return new UserResource($user);
    }



    public function destroy(User $user)
    {
        $user->delete();
        return response()->noContent();
    }
}
