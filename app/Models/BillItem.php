<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BillItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'bill_id',
        'item_reference',
        'payment_reference',
        'amount',
        'gfs_code'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
    ];

    public function bill()
    {
        return $this->belongsTo(Bill::class, 'bill_id');
    }

    public function parameter()
    {
        return $this->belongsTo(BillStructure::class, 'gfs_code', 'gfs_code');
    }
}
