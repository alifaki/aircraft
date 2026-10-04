<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Encryptable;

class Role extends Model
{
    use HasFactory, Encryptable;

    protected $appends = ['encrypted_id'];
    protected $fillable = [
        'name',
        'description',
        'is_default',
        'status'
    ];

    protected $casts = [
        'is_default' => 'boolean'
    ]; 

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'permission_role');
    }

    public function assignPermission($permission)
    {
        if (is_string($permission)) {
            $permission = Permission::where('slug', $permission)->firstOrFail();
        }
        $this->permissions()->syncWithoutDetaching($permission);
    }

    public function revokePermission($permission)
    {
        if (is_string($permission)) {
            $permission = Permission::where('slug', $permission)->firstOrFail();
        }
        $this->permissions()->detach($permission);
    }

    public function hasPermission($permission)
    {
        $permission = trim((string) $permission);
        return in_array($permission, $this->permissions->pluck('slug')->toArray());
    }
}
