<?php

namespace Database\Seeders;

use App\Models\Comment;
use App\Models\CommentLike;
use Illuminate\Database\Seeder;

class CommentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Comment::factory(50)->create();
        Comment::factory(50)->create(); // set for factory child comments
        CommentLike::factory(100)->create();
    }
}
