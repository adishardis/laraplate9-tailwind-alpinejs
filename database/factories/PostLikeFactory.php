<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Post;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Arr;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PostLike>
 */
class PostLikeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $like = Arr::random([0, 1]);
        $userId = Arr::random(User::select('id')->pluck('id')->toArray());
        $postId = Arr::random(Post::select('id')->pluck('id')->toArray());
        return [
            'post_id' => $postId,
            'user_id' => $userId,
            'is_like' => $like,
            'is_dislike' => !$like,
            'is_edited' => $like,
        ];
    }
}
