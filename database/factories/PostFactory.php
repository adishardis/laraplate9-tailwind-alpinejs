<?php

namespace Database\Factories;

use App\Models\User;
use App\Enums\PostStatus;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Arr;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Post>
 */
class PostFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $isEditedValue = Arr::random([0, 1]);
        $status = Arr::random(PostStatus::cases());
        $userIds = User::select('id')->whereHas('roles', function ($q) {
            $q->whereIn('name', ['super', 'admin']);
        })->pluck('id')->toArray();
        $userId = Arr::random($userIds);
        return [
            'title' => $this->faker->sentence(),
            'slug' => $this->faker->slug(),
            'description' => '<p>'.$this->faker->paragraph().'</p>',
            'status' => $status,
            'is_edited' => $isEditedValue,
            'user_id' => $userId
        ];
    }
}
