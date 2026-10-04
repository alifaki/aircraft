<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Section extends Model
{
    protected $fillable = [
        'department_id',
        'name',
        'code',
        'description',
        'head_id',
        'is_active'
    ];

    // Section → Department: Many-to-One
    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    // Section → Staff: One-to-Many
    public function staff()
    {
        return $this->hasMany(Staff::class);
    }

    // Section → Staff (head): One-to-One
    public function head()
    {
        return $this->belongsTo(Staff::class, 'head_id');
    }
}
