<?php
namespace App\Models\Aviation;

use Illuminate\Database\Eloquent\Model;

class SafetyEvent extends Model
{
    protected $table = 'aviation_safety_events';
    protected $guarded = ['id'];
    protected $casts = ['issues'=>'array'];
    public function duty() { return $this->belongsTo(Duty::class); }
    public function flight() { return $this->belongsTo(Flight::class); }
}
