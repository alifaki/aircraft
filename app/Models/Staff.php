<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Staff extends Model
{

    protected $table = 'staff';
    protected $fillable = [
        'user_id',
        'branch_id',
        'section_id',
        'employee_number',
        'photo_path',
        'first_name',
        'last_name',
        'initial',
        'address',
        'gender',
        'email',
        'phone',
        'nationality',
        'id_card_number',
        'date_of_birth',
        'date_of_commencement',
        'highest_education',
        'specialization',
        'position',
        'center_permit',
        'insurance_number',
        'tax_number',
        'bio',
        'status'
    ];

    // Staff → User: One-to-One (inverse)
    public function user()
    {
        return $this->hasOne(User::class);
    }

    // Staff → Department: Many-to-One
    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    // Staff → Section: Many-to-One
    public function section()
    {
        return $this->belongsTo(Section::class);
    }

    public function crewProfile()
    {
        return $this->hasOne(\App\Models\Aviation\CrewMember::class);
    }

    public function scopeTeachers($query)
    {
        return $query->whereHas('user.role', function($q) {
            $q->where('name', 'like', '%teacher%');
        });
    }
}
