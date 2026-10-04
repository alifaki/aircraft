<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Encryptable;

class Permission extends Model
{
    use HasFactory, Encryptable;

    protected $appends = ['encrypted_id'];

    protected $fillable = [
        'name',
        'slug',
        'description',
        'module'
    ];

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'permission_role');
    }
}
