<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class NewFollower extends Notification
{
    use Queueable;

    public function __construct(
        public $follower
    ) {}

    public function via($notifiable): array
    {
        return ['database'];
    }

    public function toArray($notifiable): array
    {
        return [
            'type' => 'new_follower',
            'by_user_id' => $this->follower->id,
            'by_user_name' => $this->follower->name,
        ];
    }
}

