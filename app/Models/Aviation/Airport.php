<?php
namespace App\Models\Aviation;

use Illuminate\Database\Eloquent\Model;

class Airport extends Model
{
    protected $table = 'aviation_airports';
    protected $guarded = ['id'];
}
