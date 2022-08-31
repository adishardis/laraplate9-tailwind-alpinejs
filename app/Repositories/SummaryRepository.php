<?php

namespace App\Repositories;

use App\Models\Post;
use App\Traits\DatatableTrait;
use Facades\App\Models\PostSummary;
use Illuminate\Http\Request;

class SummaryRepository
{
    use DatatableTrait;

    /**
     * Get Total Post User
     *
     * @param \App\Models\User $user
     * @return array
     */
    public function getTotalPosts($user = null)
    {
        $user = $user ?? auth()->user();
        return [
            'total' => $user->posts()->count()
        ];
    }

    /**
     * Get Total Comment User
     *
     * @param \App\Models\User $user
     * @return array
     */
    public function getTotalComments($user = null)
    {
        $user = $user ?? auth()->user();
        return [
            'total' => $user->comments()->count()
        ];
    }

    /**
     * Get Total Post Likes User
     *
     * @param \App\Models\User $user
     * @return array
     */
    public function getTotalPostLikes($user = null)
    {
        $user = $user ?? auth()->user();
        return [
            'total' => $user->post_likes()->where('is_like', 1)->count()
        ];
    }

    /**
     * Get Total Comment Likes User
     *
     * @param \App\Models\User $user
     * @return array
     */
    public function getTotalCommentLikes($user = null)
    {
        $user = $user ?? auth()->user();
        return [
            'total' => $user->comment_likes()->where('is_like', 1)->count()
        ];
    }

    /**
     * Get Total Likes User
     *
     * @param \App\Models\User $user
     * @return array
     */
    public function getTotalLikes($user = null)
    {
        $user = $user ?? auth()->user();
        $total = $this->getTotalPostLikes($user)['total'] + $this->getTotalCommentLikes($user)['total'];
        return [
            'total' => $total
        ];
    }

    /**
     * Get Total Post Dislikes User
     *
     * @param \App\Models\User $user
     * @return array
     */
    public function getTotalPostDislikes($user = null)
    {
        $user = $user ?? auth()->user();
        return [
            'total' => $user->post_likes()->where('is_dislike', 1)->count()
        ];
    }

    /**
     * Get Total Comment Dislikes User
     *
     * @param \App\Models\User $user
     * @return array
     */
    public function getTotalCommentDislikes($user = null)
    {
        $user = $user ?? auth()->user();
        return [
            'total' => $user->comment_likes()->where('is_dislike', 1)->count()
        ];
    }

    /**
     * Get Total Dislikes User
     *
     * @param \App\Models\User $user
     * @return array
     */
    public function getTotalDislikes($user = null)
    {
        $user = $user ?? auth()->user();
        $total = $this->getTotalPostDislikes($user)['total'] + $this->getTotalCommentDislikes($user)['total'];
        return [
            'total' => $total
        ];
    }

    /**
     * Get Summary User Profile
     *
     * @return array
     */
    public function getSummaryUserProfile()
    {
        $user = auth()->user();
        return [
            'likes' => $this->getTotalLikes($user)['total'] ?? 0,
            'dislikes' => $this->getTotalDislikes($user)['total'] ?? 0,
            'comments' => $this->getTotalCommentLikes($user)['total'] ?? 0,
        ];
    }

    /**
     * Get Summary post
     *
     * @param \App\Models\User $user
     * @param int $limit
     * @return array
     */
    public function getSummaryPost($user = null, $limit = 10)
    {
        $user = $user ?? auth()->user();
        $postIds = $user->posts()->pluck('id')->toArray();
        $summaries = PostSummary::query()
                            ->selectRaw("post_id, sum(likes) as likes_sum, sum(dislikes) as dislikes_sum, sum(comments) as comments_sum")
                            ->whereIn('post_id', $postIds)
                            ->groupBy('post_id')
                            ->orderBy('likes_sum')
                            ->orderBy('comments_sum')
                            ->limit($limit)
                            ->get();
        $posts = Post::whereIn('id', $summaries->pluck('post_id')->toArray())->get();
        return [
            'posts' => $posts,
            'summaries' => $summaries
        ];
    }
}
