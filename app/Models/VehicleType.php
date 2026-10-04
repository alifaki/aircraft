<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class VehicleType extends Model
{
    protected $primaryKey = 'vehicle_type_id';
    public $incrementing = true;
    public $timestamps = true;

    protected $fillable = [
        'type_name',
        'hourly_rate'
    ];

    public function parkingEntries(): HasMany
    {
        return $this->hasMany(ParkingEntry::class, 'vehicle_type_id', 'vehicle_type_id');
    }
}