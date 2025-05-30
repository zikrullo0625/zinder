<?php

namespace App\Events;

use App\Models\Message;
use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\InteractsWithSockets;
use Laravel\Reverb\Loggers\Log;

class MessageSent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public int $chat_id;
    public Message $message;

    public function __construct(int $chat_id, Message $message)
    {
        $this->chat_id = $chat_id;
        $this->message = $message;
        Log::info($this->message);
    }

    public function broadcastOn(): Channel
    {
        Log::info($this->message);

        return new Channel('chat-' . $this->chat_id);
    }

    public function broadcastWith(): array
    {
        return [
            'message' => $this->message->toArray(),
        ];
    }


    public function broadcastAs(): string
    {
        return 'message-sent';
    }
}
