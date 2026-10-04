<?php
namespace App\Models\Aviation;

use Illuminate\Database\Eloquent\Model;

class DutyActivity extends Model
{
    protected $table = 'aviation_duty_activities';
    protected $guarded = ['id'];
    protected $casts = ['starts_at'=>'datetime','ends_at'=>'datetime'];
    public function duty() { return $this->belongsTo(Duty::class); }
}
