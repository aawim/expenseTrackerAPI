<?php
namespace App\Models;
use Spatie\Permission\Models\Role as SpatieRole;
// use Illuminate\Database\Eloquent\Model;

class Role extends SpatieRole
{

    protected $fillable = [
        'name','guard_name'
    ];

     
    // public function users()
    // {
    //     return $this->hasMany(User::class);
    // }
    // public function permissions()
    // {
    //     return $this->belongsToMany(Permission::class);
    // }
}
