<?php
namespace App\Models\Aviation;

use Illuminate\Database\Eloquent\Model;

class Qualification extends Model
{
    protected $table = 'aviation_qualifications';
    protected $guarded = ['id'];
    protected $casts = ['expires_at'=>'date'];
    public function crew() { return $this->belongsTo(CrewMember::class,'crew_id'); }
    public function aircraftType() { return $this->belongsTo(AircraftType::class); }
}
