<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Message;
use App\Models\ChatRoom;
use App\Events\MessageSent;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{
    /*
    *   特定のチャットルームのメッセージを取得する
    */
    public function index($chatRoomId)
    {
        try{

            // chatroom内のメッセージ取得、送信者情報付与
            $messages = Message::where('chat_room_id', $chatRoomId)
                ->with('sender') // リレーションにより送信ユーザーも取得
                ->orderBy('created_at','asc')
                ->get();

            return response()->json([
                'chat_room_id'     => $chatRoomId,
                'messages'      => $messages
            ], 200);

        }catch(\Exceptoin $e){
            return response()->json([
                'message'       => 'Failed to fetch messages',
                'error'         => $e->getMessage()
            ], 500);
        }
        
    }

    /*
    *   メッセージの保存処理
    */
    public function store(Request $request)
    {
        
        // バリデーション
        $validated = $request->validate([
            'chat_room_id'  => 'required|exists:chat_rooms,id',
            'message'       => 'required|string|max:500',
        ]);
        
        try{
            // メッセージの作成
            $message = Message::create([
                'chat_room_id'  => $validated['chat_room_id'],
                'sender_id'     => Auth::id(),
                'message'       => $validated['message'],
            ]);

            // 作成したメッセージと関連するユーザーをロード
            // これで sender リレーションがロードされる
            $message->load('sender'); 

            // メッセージ送信イベントを発火
            broadcast(new MessageSent($message))->toOthers();
            
            return response()->json([
                'message'       => $message,
            ], 201);

        }catch(\Exception $e){
            return response()->json([
                'message'       => 'Failed to send message',
                'error'         => $e->getMessage()
            ], 500);
        }
    }

    public function show(string $id)
    {
        //
    }

    public function update(Request $request, string $id)
    {
        //
    }

    public function destroy(string $id)
    {
        //
    }
}
