<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory;

    protected $table = 'companies';

    protected $fillable = [
        "app_id", "company_name", "description", "logo",
        "location", "city", "country", "email", "phone",
        "postal_address", "website", "facebook", "twitter",
        "instagram", "youtube", "map_link", "donation", "status"
    ];

    public function branches()
    {
        return $this->hasMany(Branch::class);
    }
}
