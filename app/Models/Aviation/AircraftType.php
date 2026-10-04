<?php
namespace App\Models\Aviation;

use Illuminate\Database\Eloquent\Model;

class AircraftType extends Model
{
    protected $table = 'aviation_aircraft_types';
    protected $guarded = ['id'];
}
