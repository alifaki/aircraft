<?php
namespace App\Models\Aviation;

use Illuminate\Database\Eloquent\Model;

class Aircraft extends Model
{
    protected $table = 'aviation_aircraft';
    protected $guarded = ['id'];
    protected $casts = ['airworthiness_expires_at' => 'date', 'insurance_expires_at' => 'date'];
    public function type() { return $this->belongsTo(AircraftType::class, 'aircraft_type_id'); }
    public function homeAirport() { return $this->belongsTo(Airport::class, 'home_airport_id'); }
    public function groundings() { return $this->hasMany(Grounding::class); }
}
