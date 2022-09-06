<?php

namespace Database\Seeders;

use App\Models\Post;
use App\Models\PostLike;
use Illuminate\Database\Seeder;

class PostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Post::factory(50)->create();
        PostLike::factory(50)->create();
    }
}
