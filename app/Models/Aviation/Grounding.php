<?php
namespace App\Models\Aviation;

use Illuminate\Database\Eloquent\Model;

class Grounding extends Model
{
    protected $table = 'aviation_groundings';
    protected $guarded = ['id'];
    protected $casts = ['starts_at'=>'datetime','ends_at'=>'datetime'];
    public function aircraft() { return $this->belongsTo(Aircraft::class); }
}
