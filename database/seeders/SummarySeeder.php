<?php

namespace Database\Seeders;

use App\Models\Post;
use App\Models\PostSummary;
use App\Models\Comment;
use App\Models\CommentSummary;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SummarySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $posts = Post::with(['comments', 'likes'])->get();
        $data = [];
        foreach ($posts as $post) {
            $data[] = [
                'post_id' => $post->id,
                'likes' => $post->likes->where('is_like', 1)->count(),
                'dislikes' => $post->likes->where('is_dislike', 1)->count(),
                'comments' => $post->comments()->count(),
                'created_at' => time(),
                'updated_at' => time(),
            ];
        }
        PostSummary::insert($data);

        $data = [];
        $comments = Comment::with(['post', 'likes'])->get();

        $data = [];
        foreach ($comments as $comment) {
            $data[] = [
                'comment_id' => $comment->id,
                'likes' => $comment->likes->where('is_like', 1)->count(),
                'dislikes' => $comment->likes->where('is_dislike', 1)->count(),
                'created_at' => time(),
                'updated_at' => time(),
            ];
        }
        CommentSummary::insert($data);
    }
}
