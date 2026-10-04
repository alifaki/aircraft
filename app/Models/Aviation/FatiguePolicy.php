<?php
namespace App\Models\Aviation;

use Illuminate\Database\Eloquent\Model;

class FatiguePolicy extends Model
{
    protected $table = 'aviation_fatigue_policies';
    protected $guarded = ['id'];
    protected $casts = ['effective_from'=>'date','effective_until'=>'date','approved_at'=>'datetime'];

    public const LIMIT_FIELDS = [
        'max_flight_24h_single_minutes','max_flight_24h_multi_minutes','max_flight_7d_minutes',
        'max_flight_28d_minutes','max_flight_12mo_minutes','max_fdp_minutes',
        'max_duty_minutes','max_duty_7d_minutes','max_duty_28d_minutes','max_duty_12mo_minutes',
        'min_rest_minutes','max_sectors_per_duty','max_landings_per_duty',
    ];

    public function ready(): bool
    {
        if ($this->status !== 'approved' || !$this->approved_at || !$this->effective_from || !$this->effective_until) return false;
        foreach (self::LIMIT_FIELDS as $field) {
            if ($this->crew_category === 'cabin' && str_starts_with($field, 'max_flight_')) continue;
            if ($this->{$field} === null) return false;
        }
        return true;
    }
}
