<?php

namespace App\Notifications;

use App\Models\Post;
use App\Models\Comment;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class PostCommented extends Notification
{
    use Queueable;

    public function __construct(
        public Post $post,
        public User $commenter,
        public Comment $comment
    ) {}

    public function via($notifiable): array
    {
        return ['database'];
    }

    public function toArray($notifiable): array
    {
        return [
            'type' => 'post_commented',
            'post_id' => $this->post->id,
            'post_owner_id' => $this->post->user_id,
            'comment_id' => $this->comment->id,
            'commenter_id' => $this->commenter->id,
            'commenter_name' => $this->commenter->name,
            'comment_body' => $this->comment->body,
        ];
    }
}


