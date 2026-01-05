<?php

namespace Database\Factories;

use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Arr;

class CommentFactory extends Factory
{
    protected $model = Comment::class;

    public function definition(): array
    {
        $faker = fake('en_GB');

        $comments = [
            "Same I ran into that too. Clearing route cache fixed it.",
            "This is actually really helpful, thanks.",
            "How did you implement the policy part? I'm stuck on authorisation.",
            "Respect I would’ve rage quit by now.",
            "Try `optimize:clear` and re-run the migration, it saved me.",
            "This layout looks clean. Add pagination and you're basically done.",
            "Are you using Sail? Docker permissions were a nightmare for me.",
            "Nice. Next step: notifications when someone likes/comments.",
        ];

        $createdAt = now()->subDays(rand(0, 45))->subMinutes(rand(0, 24 * 60));

        return [
            'post_id'    => Post::inRandomOrder()->value('id') ?? Post::factory(),
            'user_id'    => User::inRandomOrder()->value('id') ?? User::factory(),
            'body'       => Arr::random($comments),
            'created_at' => $createdAt,
            'updated_at' => $createdAt,
        ];
    }
}

