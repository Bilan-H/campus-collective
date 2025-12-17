<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;
    protected $fillable = ['user_id', 'caption'];
    
    // Post belongs to one User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Post can have multiple comments
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
    public function likes()
    {
    return $this->belongsToMany(\App\Models\User::class, 'likes')->withTimestamps();
    }
    //Posts can have many hashtags and hastags belong to many posts
    public function hashtags()
    {
        return $this->belongsToMany(Hashtag::class);
    }
}
