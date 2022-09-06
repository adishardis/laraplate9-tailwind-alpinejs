<?php

namespace App\Traits;

trait NotificationTrait
{
    /**
     * Get all notifications.
     */
    public function notifications()
    {
        return $this->morphMany('App\Models\Notification', 'notifiable');
    }

    /**
     * Create New Notification
     *
     * @param  object  $user
     * @param  object  $type
     * @param  bool  $isBroadcast
     * @return void
     */
    public function createNotif($user, $type, $isBroadcast = true)
    {
        $data = $this->getContent($type);
        if (! $data) {
            return;
        }
        $data = array_merge(
            $data,
            [
                'user_id' => $user->id,
                'type' => $type,
            ]
        );
        $notif = $this->notifications()->create($data);
        $data['id'] = $notif->id;

        if ($isBroadcast) {
            \App\Events\NewNotification::dispatch($data['user_id'], $data);
        }
    }

    /**
     * Get Subject and Message based on type
     *
     * @param  string  $type
     * @return object Array
     */
    public function getContent($type)
    {
        switch ($type) {
            case 'new-post-comment':
                $comment = $this;
                $date = date('Y-m-d H:i:s', strtotime($comment->created_at));

                $userName = $comment->user->name ?? '-';

                $post = $comment->post;
                $postTitle = strlen($post->title) > 20 ? (substr($post->title, 0, 20).'...') : $post->title;

                return [
                    'subject' => "New Comment for {$postTitle}.",
                    'message' => "New comment for {$postTitle} by {$userName} at {$date}.",
                ];
                break;
            case 'new-post-like-dislike':
                $postLike = $this;
                $date = date('Y-m-d H:i:s', strtotime($postLike->updated_at));

                $userName = $postLike->author->name ?? '-';
                $likeStatus = $postLike->is_like ? 'Like' : 'Dislike';

                $post = $postLike->post;
                $postTitle = strlen($post->title) > 20 ? (substr($post->title, 0, 20).'...') : $post->title;

                return [
                    'subject' => "New ${likeStatus} for {$postTitle}.",
                    'message' => "New ${likeStatus} for {$postTitle} by {$userName} at {$date}.",
                ];
                break;
            case 'new-comment-reply':
                $comment = $this;
                $date = date('Y-m-d H:i:s', strtotime($comment->created_at));

                $userName = $comment->user->name ?? '-';

                $post = $comment->post;
                $postTitle = strlen($post->title) > 20 ? (substr($post->title, 0, 20).'...') : $post->title;

                return [
                    'subject' => "New Reply for Your Comment at {$postTitle}.",
                    'message' => "New reply for your comment at {$postTitle} by {$userName} at {$date}.",
                ];
                break;
            case 'new-comment-like-dislike':
                $commentLike = $this;
                $date = date('Y-m-d H:i:s', strtotime($commentLike->updated_at));

                $comment = $commentLike->comment;

                $userName = $commentLike->author->name ?? '-';
                $likeStatus = $commentLike->is_like ? 'Like' : 'Dislike';

                $post = $comment->post;
                $postTitle = strlen($post->title) > 20 ? (substr($post->title, 0, 20).'...') : $post->title;

                return [
                    'subject' => "New ${likeStatus} for your comment at {$postTitle}.",
                    'message' => "New ${likeStatus} for your comment at {$postTitle} by {$userName} at {$date}.",
                ];
                break;
            default:
                return null;
        }
    }
}
