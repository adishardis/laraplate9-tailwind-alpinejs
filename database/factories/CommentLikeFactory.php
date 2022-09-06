<?php

namespace Database\Factories;

use App\Models\Comment;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Arr;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CommentLike>
 */
class CommentLikeFactory extends Factory
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
        $commentId = Arr::random(Comment::select('id')->pluck('id')->toArray());
        $like = Arr::random([0, 1]);

        return [
            'comment_id' => $commentId,
            'user_id' => $userId,
            'is_like' => $like,
            'is_dislike' => ! $like,
            'is_edited' => $isEditedValue,
        ];
    }
}
