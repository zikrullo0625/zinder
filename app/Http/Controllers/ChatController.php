<?php

namespace App\Http\Controllers;

use App\Events\MessageSent;
use App\Models\Chat;
use App\Models\Message;
use App\Events\MatchFound;
use Illuminate\Support\Env;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    public function chats(Request $request): \Illuminate\Http\JsonResponse
    {
        $user_id = Auth::id();

        $chats = Chat::with('users')
            ->whereHas('users', fn($query) => $query->where('user_id', $user_id))
            ->get();

        $chats->transform(function ($chat) use ($user_id) {
            $otherUser = $chat->users->firstWhere('id', '!=', $user_id);

            $chat->name = $otherUser ? $otherUser->name : null;
            $chat->image = $otherUser ? Env::get('APP_URL') . '/storage/' . $otherUser->image : null;

            return $chat;
        });

        return response()->json([
            'chats' => $chats,
            'hasRequest' => true
        ]);
    }

    public function messages($chat_id): \Illuminate\Http\JsonResponse
    {
        $user_id = Auth::id();

        $messages = Message::where('chat_id', $chat_id)
            ->with('user:id,name')
            ->orderBy('created_at', 'asc')
            ->get()
            ->map(function ($message) use ($user_id) {
                return [
                    'user_id' => $message->user_id,
                    'user_name' => $message->user->name,
                    'content' => $message->content,
                    'from_me' => $message->user_id == $user_id,
                    'created_at' => optional($message->created_at)->format('Y-m-d H:i:s'),
                ];
            });

        $friendName = Chat::find($chat_id)->users->where('user_id', '!=', $user_id)->first()->name;

        return response()->json([
            'messages' => $messages,
            'friendName'=> $friendName
        ]);
    }


    public function sendMessage(Request $request, $chat_id)
    {
        $user_id = Auth::id();

        $message = Message::create([
            'chat_id' => $chat_id,
            'user_id' => $user_id,
            'content' => $request->input('content'),
        ]);

        $chat = Chat::find($chat_id);
        $chat->last_message = $request->input('content');
        $chat->save();

Log::info($message);
        broadcast(new MessageSent($chat_id, $message));

        return response()->json(['success' => true], 201);
    }

}
