<?php

namespace App\Events;

use App\Models\Message;
use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\InteractsWithSockets;

class MatchFound implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public int $user_id;

    public function __construct(int $user_id)
    {
        $this->user_id = $user_id;
    }

    public function broadcastOn(): Channel
    {
        return new Channel('user-' . $this->user_id);
    }

    public function broadcastAs(): string
    {
        return 'match-found';
    }
}
