<?php
namespace App\Models\Aviation;

use Illuminate\Database\Eloquent\Model;

class Duty extends Model
{
    protected $table = 'aviation_duties';
    protected $guarded = ['id'];
    protected $casts = ['report_at'=>'datetime','release_at'=>'datetime','actual_report_at'=>'datetime',
        'actual_release_at'=>'datetime','published_at'=>'datetime'];
    public function flights() { return $this->hasMany(Flight::class,'duty_id')->orderBy('departure_at'); }
    public function crew() { return $this->belongsToMany(CrewMember::class,'aviation_duty_crew','duty_id','crew_id')->withPivot('role')->withTimestamps(); }
    public function activities() { return $this->hasMany(DutyActivity::class, 'duty_id')->orderBy('starts_at'); }
    public function pilotPolicy() { return $this->belongsTo(FatiguePolicy::class,'pilot_policy_id'); }
    public function cabinPolicy() { return $this->belongsTo(FatiguePolicy::class,'cabin_policy_id'); }
    public function beginning() { return $this->actual_report_at ?: $this->report_at; }
    public function ending() { return $this->actual_release_at ?: $this->release_at; }
}
