<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChatRoomUser extends Model
{
    // fillable設定
    protected $fillable = ['chat_room_id', 'user_id'];

    protected $table = 'chat_room_users'; // 中間テーブル名を指定

    /**
     * リレーション: ChatRoomUser は ChatRoom に所属
     */
    public function chatRoom()
    {
        return $this->belongsTo(ChatRoom::class, 'chat_room_id');
    }

    /**
     * リレーション: ChatRoomUser は User に所属
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
