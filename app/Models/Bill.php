<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bill extends Model
{
    use HasFactory;

    protected $fillable = [
        'parking_entry_id',
        'control_number',
        'bill_option',
        'call_back_url',
        'customer_type',
        'customer_name',
        'customer_phone',
        'customer_email',
        'payer_name',
        'description',
        'amount',
        'paid_amount',
        'expires_at',
        'bill_reference',
        'cancellation_reason',
        'currency',
        'status',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'amount' => 'decimal:2',
        'paid_amount' => 'decimal:2',
    ];

    public function entry()
    {
        return $this->belongsTo(ParkingEntry::class, 'parking_entry_id', 'entry_id');
    }

    public function items()
    {
        return $this->hasMany(BillItem::class);
    }

    public function payments()
    {
        return $this->hasMany(PaymentFlow::class);
    }
}
