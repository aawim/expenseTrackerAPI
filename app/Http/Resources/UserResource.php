<?php
namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // return [
        //     'id'          => $this->id,
        //     'name'        => $this->name,
        //     'email'       => $this->email,
        //     'role'        => $this->getRoleNames()->first(),            // Return role name
        //     'permissions' => $this->getAllPermissions()->pluck('name'), // Optional: return permission names
        // ];

        return [
            'id'          => $this->id,
            'name'        => $this->name,
            'email'       => $this->email,
            'roles'       => $this->roles->map(function ($role) {
                return [
                    'id'          => $role->id,
                    'name'        => $role->name,
                    'permissions' => $role->permissions->pluck('name'),
                ];
            }),
            'permissions' => $this->permissions->pluck('name'), // Direct permissions
        ];
    }
}
