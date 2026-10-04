<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Municipal extends Model
{
    protected $primaryKey = 'municipal_id';
    public $incrementing = true;
    public $timestamps = true;

    protected $fillable = [
        'municipal_name',
        'region'
    ];

    public function parkingLocations(): HasMany
    {
        return $this->hasMany(ParkingLocation::class, 'municipal_id', 'municipal_id');
    }
}