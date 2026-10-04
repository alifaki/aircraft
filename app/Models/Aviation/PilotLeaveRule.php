<?php
namespace App\Models\Aviation;
use Illuminate\Database\Eloquent\Model;
class PilotLeaveRule extends Model
{
    protected $table = 'aviation_pilot_leave_rules';
    protected $guarded = ['id'];
    protected $casts = ['tracking_from'=>'datetime','last_leave_end'=>'datetime','active'=>'boolean'];
    public function crew() { return $this->belongsTo(CrewMember::class,'crew_id'); }
    public function absences() { return $this->hasMany(CrewAbsence::class,'leave_rule_id'); }
}
