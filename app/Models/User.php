<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

use App\Models\Post;
use App\Models\Comment;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    // User has many posts
    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    // User has many comments
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    // Users I follow
    public function following()
    {
        return $this->belongsToMany(
            User::class,
            'follows',
            'follower_id', // me
            'followed_id'  // them
        )->withTimestamps();
    }

    // Users who follow me
    public function followers()
    {
        return $this->belongsToMany(
            User::class,
            'follows',
            'followed_id', // me
            'follower_id'  // them
        )->withTimestamps();
    }

   
    public function isFollowing(User $other): bool
    {
        return $this->following()->where('users.id', $other->id)->exists();
    }

    public function likedPosts()
    {
     return $this->belongsToMany(Post::class, 'likes')->withTimestamps();
    }

}
