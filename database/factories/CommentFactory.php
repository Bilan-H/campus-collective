<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;
use App\Models\Post;
/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Comment>
 */
class CommentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [

            //Commenter
            'user_id' => User::factory(),
            
            //Post that has comment
            'post_id' => Post::factory(),

            //The comment text itself.
            'body' => $this->faker->sentence(9)
                        

        ];
    }
}
