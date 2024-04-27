<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $fillable = [
       'photo_url', 'caption', 'author_id', 'created_at',
    ];

    public function author()
    {
        return $this->belongsTo(User::class);
    }
}
