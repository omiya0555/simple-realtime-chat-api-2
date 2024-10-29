<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    /*
    *  全ユーザーの取得
    */
    public function index()
    {
        try {
            // ユーザー数は５人で固定
            $users = User::all();

            return response()->json([
                'users'     => $users
            ], 200);
    
        } catch (\Exception $e) {
            return response()->json([
                'message'   => 'Failed to fetch users',
                'error'     => $e->getMessage()
            ], 500);
        }
    }

    public function store(Request $request)
    {
        // ユーザー登録処理
        // 優先度　[　低　]
    }

    public function show(string $id)
    {
        // 特定のユーザーの取得
        // 優先度　[　低　]
    }

    public function update(Request $request, string $id)
    {
        // ユーザー更新処理
        // 優先度　[　低　]
    }

    public function destroy(string $id)
    {
        //
    }
}
