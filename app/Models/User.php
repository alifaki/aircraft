<?php

namespace App\Models;

use App\Traits\Confirmable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Log;
class User extends Authenticatable
{
    use Confirmable, HasApiTokens, HasFactory, Notifiable;

    protected $with = ['staffs'];

    protected $fillable = [
        'username',
        'staff_id',
        'password',
        'reset_token',
        'role_id',
        'status',
        'type',
        'parking_location_id'
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'reset_token'
    ];

    protected $casts = [
        'block_until' => 'datetime'
    ];

    // User → Staff: One-to-One
    public function staff()
    {
        return $this->belongsTo(Staff::class);
    }

    public function staffs()
    {
        return $this->belongsTo(Staff::class, 'staff_id')
            ->select(['id', 'branch_id', 'first_name', 'last_name', 'phone', 'email'])
            ->with([
                'branch:id,branch_name,company_id',
                'branch.company:id,company_name'
            ]);
    }

    // User → Role: One-to-One
    public function role()
    {
        return $this->belongsTo(Role::class);
    }

// In your User model

    /**
     * Check if user has a specific permission
     */
    public function hasPermission($permissionSlug):bool
    {
        // Super admin bypasses all checks
        if ($this->isSuperAdmin()) {
            return true;
        }

        if (!$this->role) {
            return false;
        }
    
        return $this->role->hasPermission($permissionSlug);
    }

    /**
     * Check if user has any of the given permissions
     */
    public function hasAnyPermission(array $permissionSlugs): bool
    {
        // Super admin bypasses all checks
        if ($this->isSuperAdmin()) {
            return true;
        }

        if (!$this->role) return false;

        foreach ($permissionSlugs as $slug) {
            if ($this->role->hasPermission($slug)) {
                return true;
            }
        }
        return false;
    }

    /**
     * Check if user is super admin
     */
    public function isSuperAdmin(): bool
    {
        return $this->role 
            && in_array($this->role->name, ['Super Admin', 'Administrator'], true);
    }

    public function parkingLocation(): BelongsTo
    {
        return $this->belongsTo(ParkingLocation::class, 'parking_location_id', 'location_id');
    }

}
