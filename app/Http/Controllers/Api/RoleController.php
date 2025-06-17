<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
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

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|unique:roles,name',
            'permissions' => 'array',
            'permissions.*' => 'exists:permissions,id',
        ]);

        $role = Role::create([
            'name' => $validated['name'],
        ]);

        if (!empty($validated['permissions'])) {
            $role->permissions()->sync($validated['permissions']);
        }

        return new RoleResource($role->load('permissions'));
    }

    public function show(Role $role)
    {
        return new RoleResource($role->load('permissions'));
    }

    public function update(Request $request, Role $role)
    {
        $validated = $request->validate([
            'name' => 'required|string|unique:roles,name,' . $role->id,
            'permissions' => 'array',
            'permissions.*' => 'exists:permissions,id',
        ]);

        $role->update([
            'name' => $validated['name'],
        ]);

        if (isset($validated['permissions'])) {
            $role->permissions()->sync($validated['permissions']);
        }

        return new RoleResource($role->load('permissions'));
    }

    public function destroy(Role $role)
    {
        $role->delete();
        return response()->noContent();
    }

    public function syncPermissions(Request $request, Role $role)
    {
        $validated = $request->validate([
            'permissions' => 'required|array',
            'permissions.*' => 'exists:permissions,id',
        ]);

        $role->permissions()->sync($validated['permissions']);

        return new RoleResource($role->load('permissions'));
    }
}
