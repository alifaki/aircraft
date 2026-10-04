<?php
namespace App\Models\Aviation;

use App\Models\Staff;
use Illuminate\Database\Eloquent\Model;

class CrewMember extends Model
{
    protected $table = 'aviation_crew';
    protected $guarded = ['id'];
    protected $casts = ['licence_expires_at'=>'date','medical_expires_at'=>'date'];
    public function staff() { return $this->belongsTo(Staff::class); }
    public function baseAirport() { return $this->belongsTo(Airport::class, 'base_airport_id'); }
    public function qualifications() { return $this->hasMany(Qualification::class, 'crew_id'); }
    public function absences() { return $this->hasMany(CrewAbsence::class, 'crew_id'); }
    public function restPeriods() { return $this->hasMany(RestPeriod::class, 'crew_id'); }
    public function duties() { return $this->belongsToMany(Duty::class,'aviation_duty_crew','crew_id','duty_id')->withPivot('role')->withTimestamps(); }
}
