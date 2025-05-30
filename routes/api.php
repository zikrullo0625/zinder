<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;

Route::group(['middleware' => ['web']], function () {
    Route::post('/register', [RegisterController::class, 'register'])->middleware('throttle:6,1');
    Route::post('/login', [LoginController::class, 'login']);
    Route::post('/logout', [LogoutController::class, 'logout']);

    Route::get('/check-auth', function () {
        if (Auth::check()) {
            return response()->json(['message' => 'User is authenticated'], 200);
        }
        return response()->json(['message' => 'User is not authenticated'], 401);
    });

    Route::post('/card', [UserController::class, 'createCard']);
    Route::get('/cards', [UserController::class, 'index']);
    Route::post('/update-card', [UserController::class, 'updateCard']);
    Route::get('/card', [UserController::class, 'me']);

    Route::post('/like', [UserController::class, 'likeCard']);

    Route::get('/chats', [ChatController::class, 'chats']);

    Route::post('/messages/{chat_id}', [ChatController::class, 'sendMessage']);
    Route::get('/messages/{chat_id}', [ChatController::class, 'messages']);

    Route::get('/requests', [UserController::class, 'requests']);
    Route::post('/requests/{id}/accept', [UserController::class, 'acceptRequest']);
    Route::post('/requests/{id}/reject', [UserController::class, 'rejectRequest']);

    Route::get('/user-id', function () {
        return response()->json(['user_id' => Auth::id()]);
    });
});

