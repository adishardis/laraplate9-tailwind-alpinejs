<?php

namespace Database\Factories;

use App\Models\Comment;
use App\Models\User;
use App\Models\Post;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Arr;

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
    public function definition()
    {
        $isEditedValue = Arr::random([0, 1]);
        $userId = Arr::random(User::select('id')->pluck('id')->toArray());
        $postId = Arr::random(Post::select('id')->pluck('id')->toArray());
        $parentId = Arr::random(array_merge(
            [null],
            Comment::select('id')->where('post_id', $postId)->pluck('id')->toArray()
        ));
        return [
            'post_id' => $postId,
            'user_id' => $userId,
            'parent_id' => $parentId,
            'comment' => $this->faker->sentence(),
            'is_edited' => $isEditedValue,
        ];
    }
}
