<?php

namespace App\Http\Controllers\Api;

use App\Models\Permission;
use App\Http\Controllers\Controller;
use App\Http\Resources\PermissionResource;
use App\Http\Requests\StorePermissionRequest;
use App\Http\Requests\UpdatePermissionRequest;

class PermissionController extends Controller
{
    public function index()
    {
        return PermissionResource::collection(Permission::all());
    }

    public function store(StorePermissionRequest $request)
    {
        $permission = Permission::create(array_merge($request->validated(), ['guard_name' => 'api']));
        return new PermissionResource($permission);
    }

    public function show(Permission $permission)
    {
        return new PermissionResource($permission);
    }

    public function update(UpdatePermissionRequest $request, Permission $permission)
    {
        $permission->update($request->validated());

        return new PermissionResource($permission);
    }

    public function destroy(Permission $permission)
    {
        $permission->delete();

        return response()->noContent();
    }
}