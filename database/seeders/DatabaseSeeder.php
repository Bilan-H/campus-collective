<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Carbon;
use Illuminate\Support\Arr;

use App\Models\User;
use App\Models\Post;
use App\Models\Comment;
use App\Models\Hashtag;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // -Users
        $admin = User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@campus.test',
            'password' => Hash::make('Admin2026!'),
            'role' => 'admin',
        ]);

        $users = User::factory()->count(14)->create([
            'role' => 'user',
        ]);

        $allUsers = $users->concat([$admin])->values();

        // Topics for Hashtags
        $topics = [
            'law','cs','uni','study','coffee','internship','revision',
            'london','paris','career','networking','productivity',
            'exams','library','groupwork','deadlines','notes','finance',
            'housing','commute','gym','reading','dissertation',
            'laravel','docker','debugging','javascript'
        ];

        // Captions
        $postOpeners = [
            "Quick update:", "Today’s win:", "Low-key struggling with", "Finally finished",
            "Anyone else doing", "Hot take:", "Small reminder:", "Currently obsessed with",
            "Trying to stay consistent with", "PSA:", "Not me procrastinating",
        ];

        $postBodies = [
            "I spent the morning in the library and it actually paid off.",
            "I’m trying to get ahead of lectures before deadlines hit.",
            "I fixed a bug that took hours. It was one missing route.",
            "Balancing coursework and life is genuinely harder than it looks.",
            "I’m building out features bit by bit and it’s finally coming together.",
            "If you’re stuck, take a break and come back with fresh eyes.",
            "I’m rewriting my notes and it’s making everything click.",
            "Made a tiny improvement to the UI and it feels 10x better.",
            "Docker issues nearly ended me but we move.",
            "Trying to stay disciplined with revision this week.",
            "Networking event tonight — wish me luck.",
            "Got pagination working and it looks so much cleaner.",
            "Finally figured out why my relationships were breaking. Naming matters.",
        ];

        // Comments
        $devComments = [
            "That bug would’ve finished me too.",
            "Nice — what was the root cause?",
            "Clearing caches saved me last time.",
            "Route model binding gets people all the time.",
            "This is exactly what the spec wants.",
            "Clean fix. Validation + policies = chefs kiss.",
            "Did you run migrate:fresh after changing migrations?",
            "What was in the logs?",
        ];

        $studyComments = [
            "This is so real.",
            "Same — deadlines are brutal right now.",
            "Library sessions are undefeated.",
            "Good luck — you’ve got this.",
            "How are you structuring your notes?",
            "Respect. Staying consistent is the hardest part.",
            "Revision grind is painful but worth it.",
        ];

        $lifeComments = [
            "Love this update.",
            "Honestly respect — you’re moving fast.",
            "This is motivating me to catch up.",
            "Keep going — it’s coming together.",
            "That’s actually a good approach.",
            "You’re making solid progress.",
        ];

        // Posts
        $posts = collect();

        foreach ($allUsers as $u) {
            $count = rand(2, 7);

            for ($i = 0; $i < $count; $i++) {
                $createdAt = Carbon::now()
                    ->subDays(rand(0, 45))
                    ->subMinutes(rand(0, 24 * 60));

                // up to 3 tags
                $tagCount = rand(0, 3);
                $chosen = collect($topics)->shuffle()->take($tagCount)->values();

                $caption =
                    Arr::random($postOpeners) . " " .
                    Arr::random($postBodies) .
                    ($chosen->isNotEmpty() ? " " . $chosen->map(fn ($t) => "#{$t}")->implode(' ') : "");

                $post = Post::create([
                    'user_id' => $u->id,
                    'caption' => $caption,
                    'created_at' => $createdAt,
                    'updated_at' => $createdAt,
                ]);

                // attach hashtags to pivot 
                if ($chosen->isNotEmpty()) {
                    $ids = $chosen->map(function ($slug) {
                        $slug = strtolower($slug);
                        return Hashtag::firstOrCreate(
                            ['slug' => $slug],
                            ['name' => $slug, 'slug' => $slug]
                        )->id;
                    });

                    $post->hashtags()->syncWithoutDetaching($ids->all());
                }

                $posts->push($post);
            }
        }

        // follows
        // users follow up to 8 other users
        foreach ($allUsers as $u) {
            $others = $allUsers->where('id', '!=', $u->id)->values();
            $n = rand(0, min(8, $others->count()));
            if ($n === 0) continue;

            $targets = $others->shuffle()->take($n)->pluck('id')->unique()->values();

            foreach ($targets as $followedId) {
                $createdAt = Carbon::now()
                    ->subDays(rand(0, 90))
                    ->subMinutes(rand(0, 24 * 60));

                
                $u->following()->syncWithoutDetaching([
                    $followedId => ['created_at' => $createdAt, 'updated_at' => $createdAt]
                ]);
            }
        }

        // comments
        foreach ($posts as $post) {
            $n = rand(0, 7);

            // Tag aware comments
            $cap = strtolower($post->caption ?? '');
            $pool = $lifeComments;

            if (
                str_contains($cap, '#cs') ||
                str_contains($cap, '#laravel') ||
                str_contains($cap, '#docker') ||
                str_contains($cap, '#javascript') ||
                str_contains($cap, 'docker') ||
                str_contains($cap, 'laravel')
            ) {
                $pool = $devComments;
            } elseif (
                str_contains($cap, '#law') ||
                str_contains($cap, '#study') ||
                str_contains($cap, '#exams') ||
                str_contains($cap, '#revision') ||
                str_contains($cap, 'revision') ||
                str_contains($cap, 'library')
            ) {
                $pool = $studyComments;
            }

            for ($i = 0; $i < $n; $i++) {
                $commenter = $allUsers->random();

                // comment time after post (5 mins to 7 days after), clamp to past
                $createdAt = Carbon::parse($post->created_at)->addMinutes(rand(5, 60 * 24 * 7));
                if ($createdAt->isFuture()) {
                    $createdAt = Carbon::now()->subMinutes(rand(1, 60 * 12));
                }

                Comment::create([
                    'post_id' => $post->id,
                    'user_id' => $commenter->id,
                    'body' => Arr::random($pool),
                    'created_at' => $createdAt,
                    'updated_at' => $createdAt,
                ]);
            }
        }

        // likes
        // posts liked by up to 10 users
        foreach ($posts as $post) {
            $max = min(10, $allUsers->count());
            $n = rand(0, $max);
            if ($n === 0) continue;

            $likers = $allUsers->shuffle()->take($n)->pluck('id')->unique()->values();

            foreach ($likers as $likerId) {
                $createdAt = Carbon::parse($post->created_at)->addMinutes(rand(1, 60 * 24 * 5));
                if ($createdAt->isFuture()) {
                    $createdAt = Carbon::now()->subMinutes(rand(1, 60 * 12));
                }

                $post->likes()->syncWithoutDetaching([
                    $likerId => ['created_at' => $createdAt, 'updated_at' => $createdAt]
                ]);
            }
        }
    }
}
