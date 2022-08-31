<?php

namespace App\Repositories;

use App\Models\Comment;
use App\Models\CommentLike;
use App\Models\CommentSummary;
use App\Models\Post;
use App\Models\PostSummary;
use App\Resources\CommentResource;
use App\Traits\DatatableTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CommentRepository extends BaseRepository
{
    use DatatableTrait;

    /**
     * Get Post Comments for Landing
     *
     * @return Json|Array
     */
    public function comments(Request $request)
    {
        try {
            $query = Comment::with(['user', 'childrens'])->whereNull('parent_id');
            $filters = [
                [
                    'field' => 'id',
                    'value' => $request->id,
                ],
                [
                    'field' => 'post_id',
                    'value' => $request->post_id,
                ],
            ];
            $request->sortBy = 'id';
            $request->sort = -1;
            $data = $this->filterDatatable($query, $filters, $request);
            return CommentResource::collection($data);
        } catch (\Throwable $th) {
            //throw $th;
            Log::error($th);
            return $this->setResponse(false, __('Failed get post comments'));
        }
    }

    /**
     * Add comment
     *
     * @param int $postId
     * @param int $parentId
     * @param array $data
     * @return array
     */
    public function addComment($postId, $parentId, $data)
    {
        $user = auth()->user();
        $post = Post::findOrFail($postId);
        if ($parentId) {
            $parent = Comment::where([
                'id' => $parentId,
                'post_id' => $post->id
            ])->first();

            if (!$parent) {
                return $this->setResponse(false, __('Parent not found'));
            }
        }

        $summary = $post->summary;
        if (!$summary) {
            $summary = PostSummary::create(['post_id' => $post->id]);
        }

        try {
            $data = array_merge(
                [
                    'user_id' => $user->id,
                    'post_id' => $post->id,
                    'parent_id' => $parentId
                ],
                $data
            );
            $comment = Comment::create($data);
            CommentSummary::create([
                'comment_id' => $comment->id,
            ]);

            $data = ['id' => $comment->id];
            $summary->increment('comments');

            // Notifications for author and commentator
            $comment->createNotif($post->author, 'new-post-comment');
            if ($parentId && $parent->user) {
                $comment->createNotif($parent->user, 'new-comment-reply');
            }

            return $this->setResponse(true, __('Add Comment Successfully'), $data);
        } catch (\Throwable $th) {
            //throw $th;
            Log::error($th);
            return $this->setResponse(false, __('Add Comment Failed'), '', $th->getMessage());
        }
    }

    /**
     * Update comment
     *
     * @param int $commentId
     * @param array $data
     * @return array
     */
    public function updateComment($commentId, $data)
    {
        $user = auth()->user();
        $comment = Comment::findOrFail($commentId);
        if ($comment->user_id != $user->id) {
            return $this->setResponse(false, __('You don\'t have permission'));
        }

        try {
            $comment->update($data);

            return $this->setResponse(true, __('Update Comment Successfully'));
        } catch (\Throwable $th) {
            //throw $th;
            Log::error($th);
            return $this->setResponse(false, __('Update Comment Failed'), '', $th->getMessage());
        }
    }

    /**
     * Like / Dislike Comment
     *
     * @param int $commentId
     * @param int $value (1/0)
     * @return array
     */
    public function likeDislike($commentId, $value)
    {
        $comment = Comment::findOrFail($commentId);
        $user = auth()->user();

        $summary = $comment->summary;
        if (!$summary) {
            $summary = CommentSummary::create(['comment_id' => $comment->id]);
        }

        try {
            $commentLike = $comment->userLike($user->id);
            $data = [];

            if (!$commentLike) {
                $commentLike = CommentLike::create([
                    'comment_id' => $comment->id,
                    'user_id' => $user->id
                ]);
            }

            $message = '';
            if ($value === 1) {
                $data['is_like'] = 1;
                $data['is_dislike'] = 0;
                $message = 'Comment liked';

                if (!$commentLike->is_like) {
                    $summary->increment('likes');
                }
                if ($commentLike->is_dislike) {
                    $summary->decrement('dislikes');
                }
            } elseif ($value === 0) {
                $data['is_like'] = 0;
                $data['is_dislike'] = 1;
                $message = 'Comment disliked';

                if (!$commentLike->is_dislike) {
                    $summary->increment('dislikes');
                }
                if ($commentLike->is_like) {
                    $summary->decrement('likes');
                }
            } else {
                $data['is_like'] = 0;
                $data['is_dislike'] = 0;

                if ($commentLike->is_like) {
                    $summary->decrement('likes');
                } elseif ($commentLike->is_dislike) {
                    $summary->decrement('dislikes');
                }
            }

            $commentLike->update($data);
            $summary->save();

            // Notifications for commentator
            if (in_array($value, [0, 1])) {
                $commentLike->createNotif($comment->user, 'new-comment-like-dislike');
            }

            return $this->setResponse(true, $message);
        } catch (\Throwable $th) {
            //throw $th;
            Log::error($th);
            return $this->setResponse(false, __(($value == 1 ? 'Like' : 'Dislike').' Failed'), '', $th->getMessage());
        }
    }
}
