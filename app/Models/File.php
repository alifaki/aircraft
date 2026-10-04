<?php

// app/Models/File.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class File extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'folder_id',
        'user_id',
        'file_path',
        'file_name',
        'file_type',
        'file_size',
        'description',
        'is_private'
    ];

    protected $casts = [
        'is_private' => 'boolean'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function folder()
    {
        return $this->belongsTo(Folder::class);
    }
}
