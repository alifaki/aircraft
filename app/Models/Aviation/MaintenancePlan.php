<?php
namespace App\Models\Aviation;
use Illuminate\Database\Eloquent\Model;
class MaintenancePlan extends Model
{
    protected $table = 'aviation_maintenance_plans';
    protected $guarded = ['id'];
    protected $casts = ['last_completed_at'=>'datetime','tracking_from'=>'datetime','active'=>'boolean'];
    public function aircraft() { return $this->belongsTo(Aircraft::class); }
    public function tasks() { return $this->hasMany(MaintenanceTask::class,'plan_id'); }
}
