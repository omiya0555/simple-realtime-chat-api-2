<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChatRoom extends Model
{
    // fillable設定
    protected $fillable = [
        'room_name',
    ];

    // 1つのチャットルームには多くのメッセージがある
    public function messages()
    {
        return $this->hasMany(Message::class, 'chat_room_id');
    }

    // 1つのチャットルームには多くのユーザーが参加する
    public function users()
    {
        return $this->belongsToMany(User::class, 'chat_room_users', 'chat_room_id', 'user_id');
    }
}
