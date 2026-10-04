<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\HasOne;

class ParkingEntry extends Model
{
    protected $primaryKey = 'entry_id';
    public $incrementing = true;
    public $timestamps = true;

    protected $fillable = [
        'entry_id',
        'vehicle_type_id',
        'plate_number',
        'phone_number',
        'entry_time',
        'exit_time',
        'location_id',
        'officer_id',
        'total_amount',
        'rate_per_hour'
    ];

    protected $casts = [
        'entry_time' => 'datetime',
        'exit_time' => 'datetime',
        'total_amount' => 'decimal:2',
        'rate_per_hour' => 'decimal:2'
    ];

    public function bill(): HasOne
    {
        return $this->hasOne(Bill::class);
    }

    public function vehicleType(): BelongsTo
    {
        return $this->belongsTo(VehicleType::class, 'vehicle_type_id', 'vehicle_type_id');
    }

    public function location(): BelongsTo
    {
        return $this->belongsTo(ParkingLocation::class, 'location_id', 'location_id');
    }

    public function officer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'officer_id');
    }

    protected function parkingDuration(): Attribute
    {
        return Attribute::make(
            get: function () {
                if (!$this->exit_time) {
                    return null;
                }
                return $this->entry_time->diffInHours($this->exit_time);
            }
        );
    }

    public function calculateTotalAmount(): void
    {
        if ($this->exit_time && $this->rate_per_hour) {
            // Calculate the total full (rounded up) hours between entry and exit
            $minutes = $this->entry_time->diffInMinutes($this->exit_time);
            $hours = ceil($minutes / 60);
            $this->total_amount = $hours * $this->rate_per_hour;
        }
    }

    protected static function boot()
    {
        parent::boot();

        static::updating(function ($model) {
            if ($model->isDirty('exit_time') && $model->exit_time) {
                $model->calculateTotalAmount();
            }
        });
    }
}