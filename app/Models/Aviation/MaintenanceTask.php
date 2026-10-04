<?php
namespace App\Models\Aviation;
use Illuminate\Database\Eloquent\Model;
class MaintenanceTask extends Model
{
    protected $table = 'aviation_maintenance_tasks';
    protected $guarded = ['id'];
    protected $casts = ['due_at'=>'datetime','scheduled_start'=>'datetime','scheduled_end'=>'datetime','completed_at'=>'datetime'];
    public function plan() { return $this->belongsTo(MaintenancePlan::class,'plan_id'); }
}
