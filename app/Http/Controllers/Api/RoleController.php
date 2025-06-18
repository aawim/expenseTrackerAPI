<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreRoleRequest;
use App\Http\Requests\UpdateRoleRequest;
use App\Http\Resources\RoleResource;
use App\Models\Role;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    public function index()
    {
        $roles = Role::with('permissions')->get();
        return RoleResource::collection($roles);
    }

    public function store(StoreRoleRequest $request)
    {

        $role = Role::create([
            'name' => $request['name'],
        ]);

        if (! empty($request['permissions'])) {
            $role->permissions()->sync($request['permissions']);
        }

        return new RoleResource($role->load('permissions'));
    }

    public function show(Role $role)
    {
        return new RoleResource($role->load('permissions'));
    }

    public function update(UpdateRoleRequest $request, Role $role)
    {

        $role->update([
            'name' => $request['name'],
        ]);

        if (isset($request['permissions'])) {
          $role->syncPermissions($request['permissions']);
        }

        return new RoleResource($role->load('permissions'));
    }

    public function destroy(Role $role)
    {
        // $role->delete();
        // // return response()->noContent();

        // return response()->json(['message' => 'Role deleted successfully']);

        try {
            $role->delete();
            return response()->json(['message' => 'Deleted']);
        } catch (\Throwable $e) {
            return response()->json([
                'message' => 'Failed to delete role',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

    public function syncPermissions(Request $request, Role $role)
    {
        $validated = $request->validate([
            'permissions'   => 'required|array',
            'permissions.*' => 'exists:permissions,id',
        ]);

        $role->permissions()->sync($validated['permissions']);

        return new RoleResource($role->load('permissions'));
    }
}
