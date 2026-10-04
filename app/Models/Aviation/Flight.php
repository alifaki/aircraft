<?php
namespace App\Models\Aviation;

use Illuminate\Database\Eloquent\Model;

class Flight extends Model
{
    protected $table = 'aviation_flights';
    protected $guarded = ['id'];
    protected $casts = ['flight_date'=>'date','departure_at'=>'datetime','arrival_at'=>'datetime',
        'actual_off_at'=>'datetime','actual_on_at'=>'datetime'];
    public function aircraft() { return $this->belongsTo(Aircraft::class); }
    public function origin() { return $this->belongsTo(Airport::class,'origin_id'); }
    public function destination() { return $this->belongsTo(Airport::class,'destination_id'); }
    public function duty() { return $this->belongsTo(Duty::class); }
    public function beginning() { return $this->actual_off_at ?: $this->departure_at; }
    public function ending() { return $this->actual_on_at ?: $this->arrival_at; }
}
