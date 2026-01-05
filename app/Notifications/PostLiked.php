<?php

namespace App\Notifications;

use App\Models\Post;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class PostLiked extends Notification
{
    use Queueable;

    public function __construct(
        public Post $post,
        public User $liker
    ) {}

    public function via($notifiable): array
    {
        // store in DB 
        return ['database'];
    }

    public function toDatabase($notifiable): array
    {
        return [
            'type' => 'post_liked',
            'post_id' => $this->post->id,
            'post_caption' => $this->post->caption,
            'liker_id' => $this->liker->id,
            'liker_name' => $this->liker->name,
        ];
    }
}




