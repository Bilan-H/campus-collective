<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Hashtag extends Model
{
    use HasFactory;

protected $fillable = ['name', 'slug'];

// Hashtags can be in many posts and Posts can have many hashtags
    public function posts()
    {
        return $this->belongsToMany(Post::class);
    }
}
