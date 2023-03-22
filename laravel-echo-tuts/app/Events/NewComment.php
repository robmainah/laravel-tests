<?php

namespace App\Events;

use App\Models\Comment;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NewComment implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $comment;

    public function __construct(Comment $comment)
    {
        $this->comment = $comment;
    }

    public function broadcastOn(): array
    {
        return [
            new PresenceChannel('posts.' . $this->comment->post->id),
        ];
    }

    public function broadcastAs()
    {
        return 'new-comment';
    }

    public function broadcastWith() 
    {
        return [
            'body' => $this->comment->body,
            'created_at' => $this->comment->created_at->diffForHumans(),
            'user' => [
                'name' => $this->comment->user->name,
                'avatar' => 'http://lorempixel/50/50',
            ]
        ];
    }
}
