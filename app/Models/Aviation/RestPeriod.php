<?php
namespace App\Models\Aviation;

use Illuminate\Database\Eloquent\Model;

class RestPeriod extends Model
{
    protected $table = 'aviation_crew_rest_periods';
    protected $guarded = ['id'];
    protected $casts = ['starts_at'=>'datetime','ends_at'=>'datetime'];
    public function crew() { return $this->belongsTo(CrewMember::class,'crew_id'); }
}
