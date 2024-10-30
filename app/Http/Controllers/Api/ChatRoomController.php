<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\ChatRoom;
use App\Models\ChatRoomUser;
use Illuminate\Support\Facades\DB;

class ChatRoomController extends Controller
{

    /*
    *  ユーザーが参加しているチャットルームを取得
    */
    public function index()
    {
        try {
            // ログインしているユーザーを取得
            $user = auth()->user();
    
            // ユーザーが参加しているチャットルームを取得
            // with('users')により、チャットに参加している他のユーザーも取得
            $chatRooms = $user->chatRooms()->with('users')->get();
    
            return response()->json([
                'message'       => 'Chat rooms fetched successfully',
                'chat_rooms'    => $chatRooms
            ], 200);
    
        } catch (\Exception $e) {
            return response()->json([
                'message'       => 'Failed to fetch chat rooms',
                'error'         => $e->getMessage()
            ], 500);
        }
    }

    /*
    *  チャットの対向ユーザー情報を取得
    */
    public function getOtherUser($chatRoomId)
    {
        try {
            $currentUserId = auth()->id(); // 現在のログインユーザーID
    
            // 対象のチャットルームの他のユーザーを取得
            $otherUser = \DB::table('chat_room_users')
                ->where('chat_room_id', $chatRoomId)
                ->where('user_id', '!=', $currentUserId)
                ->join('users', 'chat_room_users.user_id', '=', 'users.id')
                ->join('user_icons', 'users.icon_id', '=', 'user_icons.id')
                ->select('users.id', 'users.name', 'users.email', 'user_icons.path')
                ->first();
    
            if (!$otherUser) {
                return response()->json(['message' => 'No other user found in the chat room.'], 404);
            }
    
            return response()->json($otherUser, 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to fetch other user', 'error' => $e->getMessage()], 500);
        }
    }

     
    /*
    *   新しいチャットルームを作成する
    */
    public function getOrCreateChatRoom(Request $request)
    {
        try{
            DB::beginTransaction();

            // バリデーション
            $validated = $request->validate([
                'other_user_id' => 'required|exists:users,id',
            ]);

            // ログインしているユーザーのID
            $currentUserId = auth()->id();
            $otherUserId = $validated['other_user_id'];

            // 既存のチャットルームを確認
            $existingRoom = ChatRoomUser::where('user_id', $currentUserId)
                ->whereHas('chatRoom.users', function ($query) use ($otherUserId) {
                    $query->where('user_id', $otherUserId);
                })
                ->first();

            if ($existingRoom) {
                // 既存のチャットルームが存在する場合、そのIDを返す
                return response()->json([
                    'chat_room_id' => $existingRoom->chat_room_id,
                ], 200);
            }

            // 新しいチャットルームを作成
            $chatRoom = ChatRoom::create([
                'room_name' => null, // 個別チャットのため、デフォルトの名前
            ]);

            // チャットルームにユーザーを追加
            $chatRoom->users()->attach([$currentUserId, $otherUserId]);

            DB::commit();

            return response()->json([
                'chat_room_id' => $chatRoom->id,
            ], 201);

        } catch (\Exception $e) {

            DB::rollBack();

            return response()->json([
                'message'       => 'ChatRoom creation failed!',
                'error'         => $e->getMessage()
            ], 500);
        }
    }


    // public function store(Request $request)
    // {
    //     // バリデーション
    //     $validated = $request->validate([
    //         'room_name'       => 'sometimes|required|max:30',
    //         'chat_user_ids'   => 'required|array|min:2', // 2人以上
    //         'chat_user_ids.*' => 'exists:users,id',
    //         'group_chat_flag' => 'required|boolean',
    //     ]);

    //     try {
    //         DB::beginTransaction();

    //         // チャットルームの作成

    //         // グループチャットなら入力された名前を設定
    //         $chatRoom = ChatRoom::create([
    //             'room_name'     => $validated['group_chat_flag'] ? $validated['room_name'] : null,
    //         ]);

    //         // チャットルームにユーザーを追加
    //         foreach ($validated['chat_user_ids'] as $chatUserId) {
    //             $chatRoom->users()->attach($chatUserId);
    //         }
    //         /*
    //             attach / detach メソッド
    //             中間テーブルへのデータ操作に便利
    //             シンプルかつ明示的にリレーションを利用
    //         */

    //         DB::commit();

    //         return response()->json([
    //             'message'       => 'ChatRoom created successfully!',
    //             'chat_room'     => $chatRoom
    //         ], 201);

    //     } catch (\Exception $e) {
    //         DB::rollBack();

    //         return response()->json([
    //             'message'       => 'ChatRoom creation failed!',
    //             'error'         => $e->getMessage()
    //         ], 500);
    //     }
    // }

    public function show(string $id)
    {
        // 特定のチャットルームの情報を返す
        // 優先度　[　低　]
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
