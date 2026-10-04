<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Department extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'code',
        'description',
        'head_ofd_id',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'deactivated_at' => 'datetime'
    ];

    // Relationship to staff member who heads the department
    public function head()
    {
        return $this->belongsTo(Staff::class, 'head_ofd_id');
    }

    // Relationship to sections within this department
    public function sections()
    {
        return $this->hasMany(Section::class);
    }

    // Automatically set deactivated_at when is_active changes
    public static function boot()
    {
        parent::boot();

        static::updating(function ($department) {
            if ($department->isDirty('is_active') && !$department->is_active) {
                $department->deactivated_at = now();
            }
        });
    }
}
