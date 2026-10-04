<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentFlow extends Model
{
    public $incrementing = true;

    protected $fillable = [
        'bill_id',
        'paidAmount',
        'serviceProvider',
        'receiptNumber',
        'billRefrence',
        'PyrName',
        'CtrAccNum',
        'payerPhone',
        'remark',
        'paidAt'
    ];

    protected $casts = [
        'paidAmount' => 'decimal:2',
        'paidAt' => 'datetime'
    ];

    public function bill()
    {
        return $this->belongsTo(Bill::class, 'bill_id');
    }
}
