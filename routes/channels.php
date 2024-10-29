<?php

use Illuminate\Support\Facades\Broadcast;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/
// ユーザーごとのプライベートチャネルの例
// Broadcast::channel('chat-room', function ($user, $roomId) {
//     // 認証ロジック: ユーザーがそのルームに所属しているかなど実装
//     return true;
// });
// Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
//     return (int) $user->id === (int) $id;
// });
