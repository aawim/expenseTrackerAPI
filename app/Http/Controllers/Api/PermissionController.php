<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\PermissionResource;
use App\Models\Permission;
use Illuminate\Http\Request;

class PermissionController extends Controller
{
    public function index()
    {
        return PermissionResource::collection(Permission::all());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|unique:permissions,name',
            //'guard_name' => 'required|string'
        ]);

        $permission = Permission::create(array_merge($validated, ['guard_name' => 'api']));
        return new PermissionResource($permission);
    }

    public function show(Permission $permission)
    {
        return new PermissionResource($permission);
    }

    public function update(Request $request, Permission $permission)
    {
        $validated = $request->validate([
            'name' => 'required|string|unique:permissions,name,' . $permission->id,
        ]);

        $permission->update($validated);

        return new PermissionResource($permission);
    }

    public function destroy(Permission $permission)
    {
        $permission->delete();

        return response()->noContent();
    }
}
