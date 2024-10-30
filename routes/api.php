<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\ChatRoomController;
use App\Http\Controllers\Api\MessageController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// 認証関連のルート
Route::post('login', [AuthController::class, 'login']);  // ログイン
Route::post('logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');  // ログアウト（認証が必要）
Route::get('/me', [AuthController::class, 'me'])->middleware('auth:sanctum');

// 認証が必要なルート群
Route::middleware('auth:sanctum')->group(function () {

    Route::get('users', [UserController::class, 'index']);
    Route::get('users/{id}', [UserController::class, 'show']);
    Route::get('user' , function (Request $request){
        return $request->user();
    });

    // チャットルーム関連のルート

    // ユーザーが参加しているチャットルーム一覧
    Route::get('chat-rooms', [ChatRoomController::class, 'index']);

    // チャットの対向ユーザー情報を取得
    Route::get('chat-room/{chatRoomId}/other-user', [ChatRoomController::class, 'getOtherUser']);

    // 新しいチャットルームを作成
    Route::post('chat-room', [ChatRoomController::class, 'getOrCreateChatRoom']);
    
    //Route::post('chat-rooms', [ChatRoomController::class, 'store']); // 新しいチャットルームを作成
    
    // チャットルーム関連のルート
    Route::get('chat-users', [ChatRoomUserController::class, 'index']);  // チャットルームのユーザー達

    // メッセージ関連のルート
    Route::get('chat-rooms/{chatRoomId}/messages', [MessageController::class, 'index']);  // 特定のチャットルームのメッセージ一覧
    Route::post('messages', [MessageController::class, 'store']);  // メッセージの送信
});




use App\Events\MessageSent;
use App\Models\Message;
Route::get('/test-broadcast', function () {
    $message = Message::find(6);
    broadcast(new MessageSent($message))->toOthers();
    return 'Event has been broadcasted!';
});