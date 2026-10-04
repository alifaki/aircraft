<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ParkingLocation extends Model
{
    protected $primaryKey = 'location_id';
    protected $table = 'parking_locations';
    public $incrementing = true;
    public $timestamps = true;

    protected $fillable = [
        'location_name',
        'municipal_id'
    ];

    public function municipal(): BelongsTo
    {
        return $this->belongsTo(Municipal::class, 'municipal_id', 'municipal_id');
    }

    public function parkingEntries(): HasMany
    {
        return $this->hasMany(ParkingEntry::class, 'location_id', 'location_id');
    }
}