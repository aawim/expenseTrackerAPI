<?php
namespace App\Http\Controllers\Api;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;

class UserController extends Controller
{
    public function index(Request $request)
    {

        $user = $request->user(); // Get the authenticated user

        if (! ($user->hasRole('Admin') || $user->hasPermissionTo('create_user'))) {
            return response()->json(['message' => 'Forbidden. Admins with permission only.'], 403);
        }
        return UserResource::collection(User::with('role')->paginate());

    }

    public function store(StoreUserRequest $request)
    {
        // $data = $request->validate([
        //     'name'     => 'required',
        //     'email'    => 'required|email|unique:users',
        //     'password' => 'required|min:6',
        //     // 'role_id'  => 'nullable|exists:roles,id',
        // ]);

        $request['password'] = bcrypt($request['password']);
        $user = User::create($request->toArray());
        return new UserResource($user->load('role'));
    }

    public function update(UpdateUserRequest $request, $id)
    {
        // Prevent users from changing their own roles
           
        $user = $request->user(); // Get the authenticated user

        if (! ($user->hasRole('Admin') || $user->hasPermissionTo('edit_user'))) {
            return response()->json(['message' => 'Forbidden.You cannot change your own role or permission.'], 403);
        }



        // // Validate user data
        // $data = $request->validate([
        //     'name'  => 'sometimes|string|max:255',
        //     'email' => 'sometimes|email',
        //     //'role_id' => 'nullable|exists:roles,id',
        // ]);

        $user = User::findOrFail($id);

        // Update name/email if present
        if (isset($data['name'])) {
            $user->name = $request['name'];
        }

        if (isset($data['email'])) {
            $user->email = $request['email'];
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
