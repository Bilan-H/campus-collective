<?php

namespace Database\Factories;

use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Arr;

class PostFactory extends Factory
{
    protected $model = Post::class;

    public function definition(): array
    {
        $faker = fake('en_GB');

        $captions = [
            "Just finished my lecture notes anyone else revising for the deadline?",
            "Coffee + coursework + chaos. We move.",
            "Quick question: what's the best way to structure the report discussion section?",
            "Trying to get my Laravel app polished before submission. Any tips for pagination + UI?",
            "Group project admin is testing my patience, but the code is finally cooperating.",
            "Spent the whole evening debugging a route issue. It was one missing name().",
            "If you see me in the library, mind your business I'm fighting for my grade.",
            "Deployed locally, works. Deployed in Sail, breaks. Classic.",
            "Finally got likes + comments working. Next: images + notifications.",
            "Late night study session drop your best productivity hacks.",
        ];

        $tagPool = [
            'law','cs','laravel','webdev','revision','exams','campus','study','debugging','php','docker','frontend'
        ];

        // 1–3 hashtags
        $tags = collect($tagPool)->shuffle()->take(rand(1, 3))->map(fn ($t) => "#{$t}")->implode(' ');

        //  timestamps over last 45 days
        $createdAt = now()->subDays(rand(0, 45))->subMinutes(rand(0, 24 * 60));

        return [
            'user_id'    => User::inRandomOrder()->value('id') ?? User::factory(),
            'caption'    => Arr::random($captions) . " " . $tags,
            'image_path' => null, 
            'created_at' => $createdAt,
            'updated_at' => $createdAt,
        ];
    }
}


