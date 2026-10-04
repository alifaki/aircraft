<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IpRestriction extends Model
{
    protected $fillable = [
        'ip_address', 'description', 'type', 'is_active', 'created_by'
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
