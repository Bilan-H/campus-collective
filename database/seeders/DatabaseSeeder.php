<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Post;
use App\Models\Comment;
use App\Models\Hashtag;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
       User::factory(5)
           ->has(
               Post::factory(3)
                   ->has(Comment::factory(4))
           )
           -> create();

        
      $hashtags = Hashtag::factory(10)->create();

        Post::all()->each(function($post) use ($hashtags) {
            $post->hashtags()->attach(
                $hashtags->random(rand(1, 4))->pluck('id')->toArray()
            );
        });
    }
}

