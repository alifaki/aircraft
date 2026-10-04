<?php
namespace App\Traits;

use Illuminate\Support\Facades\Crypt;

trait Encryptable
{
    public function getEncryptedIdAttribute()
    {
        return Crypt::encrypt($this->id);
    }

    public function scopeFindByEncryptedId($query, $encryptedId)
    {
        try {
            $id = Crypt::decrypt($encryptedId);
            return $query->findOrFail($id);
        } catch (\Exception $e) {
            abort(404);
        }
    }
}