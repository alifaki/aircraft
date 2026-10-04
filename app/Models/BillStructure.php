<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BillStructure extends Model
{
    use HasFactory;

    protected $fillable = [
        'short_name',
        'name',
        'gfs_code',
        'group',
        'category'
    ];

    public function billItems()
    {
        return $this->hasMany(BillItem::class, 'gfs_code', 'gfs_code');
    }

    public function expenditures()
    {
        return $this->hasMany(Expenditure::class);
    }

    public function incomes()
    {
        return $this->hasMany(Income::class);
    }
}
