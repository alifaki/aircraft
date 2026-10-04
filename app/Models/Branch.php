<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Municipal;

class Branch extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id',
        'branch_name',
        'branch_code',
        'location',
        'city',
        'country',
        'manager_name',
        'manager_phone',
        'manager_email',
        'status'
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function bankAccounts()
    {
        return $this->hasMany(BankAccount::class);
    }

    public function bills()
    {
        return $this->hasMany(Bill::class);
    }
    public function municipal()
    {
        return $this->belongsTo(Municipal::class, 'municipal_id', 'municipal_id');
    }
}
