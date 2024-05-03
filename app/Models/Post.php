<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Post extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $fillable = [
       'photo_url', 'caption', 'author_id', 'created_at',
    ];

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
