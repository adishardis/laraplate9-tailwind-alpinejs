<?php

namespace App\Repositories;

use App\Enums\PostStatus;
use App\Models\Post;
use App\Models\PostLike;
use App\Models\PostSummary;
use App\Resources\PostResource;
use App\Resources\Super\PostResource as SuperPostResource;
use App\Traits\DatatableTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PostRepository extends BaseRepository
{
    use DatatableTrait;

    /**
     * Get Datatables Posts for Super and Admin
     *
     * @return Json|array
     */
    public function datatable(Request $request)
    {
        try {
            $user = auth()->user();
            $query = Post::with(['author']);
            if (! $user->hasRole('super')) {
                $query = $query->whereBelongsTo($user, 'author');
            }
            $filters = [
                [
                    'field' => 'id',
                    'value' => $request->id,
                ],
                [
                    'field' => 'title',
                    'value' => $request->title,
                    'query' => 'like',
                ],
                [
                    'field' => 'description',
                    'value' => $request->description,
                    'query' => 'like',
                ],
            ];
            $request->sortBy = $request->sortBy ?? 'id';
            $request->sort = $request->sort ?? -1;
            $data = $this->filterDatatable($query, $filters, $request);

            return SuperPostResource::collection($data);
        } catch (\Throwable $th) {
            //throw $th;
            Log::error($th);

            return $this->setResponse(false, __('Failed get posts'));
        }
    }

    /**
     * Get Data Posts for Landing
     *
     * @return Json|array
     */
    public function landingData(Request $request)
    {
        try {
            $query = Post::with(['author'])->whereStatus(PostStatus::PUBLISHED);
            $filters = [
                [
                    'field' => 'id',
                    'value' => $request->id,
                ],
                [
                    'field' => 'title',
                    'value' => $request->title,
                    'query' => 'like',
                ],
                [
                    'field' => 'description',
                    'value' => $request->description,
                    'query' => 'like',
                ],
            ];
            $request->sortBy = $request->sortBy ?? 'id';
            $request->sort = $request->sort ?? -1;
            $data = $this->filterDatatable($query, $filters, $request);

            return PostResource::collection($data);
        } catch (\Throwable $th) {
            //throw $th;
            Log::error($th);

            return $this->setResponse(false, __('Failed get posts landing'));
        }
    }

    /**
     * Create Post
     *
     * @param  array  $data
     * @return App/Models/Post
     */
    public function createPost($data)
    {
        try {
            $data['user_id'] = null;
            $data = Post::create($data);

            return $this->setResponse(true, __('Create post successfully'), $data);
        } catch (\Throwable $th) {
            //throw $th;
            Log::error($th);

            return $this->setResponse(false, __('Create post failed'), '', $th->getMessage());
        }
    }

    /**
     * Update Post
     *
     * @param App/Models/Post $post
     * @param  array  $data
     * @return App/Models/Post
     */
    public function updatePost($post, $data)
    {
        try {
            $data['is_edited'] = true;
            $post->update($data);

            return $this->setResponse(true, __('Update post successfully'), $post);
        } catch (\Throwable $th) {
            //throw $th;
            Log::error($th);

            return $this->setResponse(false, __('Update post failed'), '', $th->getMessage());
        }
    }

    /**
     * Like / Dislike Post
     *
     * @param  int  $postId
     * @param  int  $value (1/0)
     * @return array
     */
    public function likeDislike($postId, $value)
    {
        $post = Post::findOrFail($postId);
        $user = auth()->user();

        $summary = $post->summary;
        if (! $summary) {
            $summary = PostSummary::create(['post_id' => $post->id]);
        }

        try {
            $postLike = $post->userLike($user->id);
            $data = [];
            $message = '';

            if (! $postLike) {
                $postLike = PostLike::create([
                    'post_id' => $post->id,
                    'user_id' => $user->id,
                ]);
            }

            if ($value === 1) {
                $data['is_like'] = 1;
                $data['is_dislike'] = 0;
                $message = __('Post liked');

                if (! $postLike->is_like) {
                    $summary->increment('likes');
                }
                if ($postLike->is_dislike) {
                    $summary->decrement('dislikes');
                }
            } elseif ($value === 0) {
                $data['is_like'] = 0;
                $data['is_dislike'] = 1;
                $message = __('Post disliked');

                if (! $postLike->is_dislike) {
                    $summary->increment('dislikes');
                }
                if ($postLike->is_like) {
                    $summary->decrement('likes');
                }
            } else {
                $data['is_like'] = 0;
                $data['is_dislike'] = 0;

                if ($postLike->is_like) {
                    $summary->decrement('likes');
                } elseif ($postLike->is_dislike) {
                    $summary->decrement('dislikes');
                }
            }

            $postLike->update($data);
            $summary->save();

            // Notification for author
            if (in_array($value, [0, 1])) {
                $postLike->createNotif($post->author, 'new-post-like-dislike');
            }

            return $this->setResponse(true, $message);
        } catch (\Throwable $th) {
            //throw $th;
            Log::error($th);

            return $this->setResponse(false, __(($value == 1 ? 'Like' : 'Dislike').' Failed'), '', $th->getMessage());
        }
    }
}
