<?php

namespace App\Http\Controllers;

use App\Events\MatchFound;
use App\Models\Card;
use App\Models\Chat;
use App\Models\Friend;
use App\Models\Like;
use App\Models\Room;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use function Laravel\Prompts\text;

class UserController extends Controller
{
    public function requests(Request $request)
    {
        $user = Auth::user();
        $requests = $user->incomingRequests()
            ->where('status', 'pending')
            ->with('user') // не users, а user, если у тебя связь belongsTo
            ->get();

        return response()->json([
            'requests' => $requests
        ]);

    }

    public function acceptRequest($id): \Illuminate\Http\JsonResponse
    {
        $request = \App\Models\Request::find($id);

        $chat = new Chat();
        $chat->save();
        $chat->users()->attach([Auth::id(), $request->user_id]);

        broadcast(new MatchFound($request->user_id));

        $request->delete();

        return response()->json([
            'success' => true,
        ]);
    }
    public function rejectRequest($id): \Illuminate\Http\JsonResponse
    {
        \App\Models\Request::find($id)->delete();

        return response()->json([
            'success' => true,
        ]);
    }
    public function likeCard(Request $request)
    {
        $authId = Auth::id();
        $targetUserId = $request->get('user_id');

        $chatExists = Chat::whereHas('users', function($query) use ($authId) {
            $query->where('users.id', $authId);
        })
            ->whereHas('users', function($query) use ($targetUserId) {
                $query->where('users.id', $targetUserId);
            })
            ->exists();

        if ($chatExists) {
            return response()->json(['message' => 'Вы уже поставили лайк этому челу'], 200);
        }

        $requestExists = \App\Models\Request::where('user_id', $authId)
            ->where('recipient_id', $targetUserId)
            ->exists();

        if ($requestExists) {
            return response()->json(['message' => 'Вы уже отправили запрос этому пользователю'], 200);
        }

        $newRequest = new \App\Models\Request;
        $newRequest->user_id = $authId;
        $newRequest->recipient_id = $targetUserId;
        $newRequest->status = 'pending';
        $newRequest->save();

        return response()->json(['message' => 'Вы успешно поставили лайк'], 200);
    }



    public function index(Request $request)
    {
        $user = Auth::user();

        $chatIds = $user->chats()->pluck('chats.id');

        $chatUsers = DB::table('chat_user')
            ->whereIn('chat_id', $chatIds)
            ->where('user_id', '!=', $user->id)
            ->pluck('user_id');

        $requestedUsers = DB::table('requests')
            ->where('user_id', $user->id)
            ->pluck('recipient_id');

        $receivedRequestsFrom = DB::table('requests')
            ->where('recipient_id', $user->id)
            ->pluck('user_id');

        $excludedUserIds = $chatUsers
            ->merge($requestedUsers)
            ->merge($receivedRequestsFrom)
            ->unique();

        $users = User::where('gender', '!=', $user->gender)
            ->whereNotIn('id', $excludedUserIds)
            ->get();

        return response()->json($users, 200);
    }

    public function createCard(Request $request){
        $request->validate([
            'image' => 'required|mimes:jpg,jpeg,png,gif|max:2048',
            'name' => 'required|string|max:255',
            'age' => 'required|integer|min:5',
            'gender' => 'required|in:male,female',
            'description' => 'required|string',
        ]);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('profile_images', 'public');
        } else {
            return response()->json(['message' => 'Выберите фото!'], 401);
        }

        $profile = Auth::user();

        $profile->name = $request->input('name');
        $profile->age = $request->input('age');
        $profile->gender = $request->input('gender');
        $profile->description = $request->input('description');
        $profile->image = $path;
        $profile->startuped = true;
        $profile->save();

        return response()->json([
            'message' => 'Профиль успешно сохранен!',
            'user' => $profile->fresh()
        ], 200);
    }

    public function me(){
        $me = Auth::user();
        return response()->json($me);
    }

    public function updateCard(Request $request){
        $profile = User::find(Auth::id());

        if ($request->input('status') === false) {
            $request->validate([
                'name' => 'required|string|max:255',
                'age' => 'required|integer|min:5',
                'gender' => 'required|string|in:male,female',
                'description' => 'required|string',
            ]);

            $profile->update([
                'name' => $request->name,
                'age' => $request->age,
                'gender' => $request->gender,
                'description' => $request->description,
                'image' => str_replace('http://127.0.0.1:8000/storage/', '', $request->image),
            ]);

        } else {
            $request->validate([
                'image' => 'required|image|mimes:jpg,jpeg,png,gif|max:2048',
                'name' => 'required|string|max:255',
                'age' => 'required|integer|min:5',
                'gender' => 'required|string|in:male,female',
                'description' => 'required|string',
            ]);

            $path = $request->file('image')->store('profile_images', 'public');

            $profile->update([
                'name' => $request->name,
                'age' => $request->age,
                'gender' => $request->gender,
                'description' => $request->description,
                'image' => $path,
            ]);
        }
        return response()->json(['message' => 'Профиль успешно обновлён!'], 200);
    }
}
