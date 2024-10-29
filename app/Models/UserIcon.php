<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserIcon extends Model
{
    // fillable設定
    protected $fillable = ['path'];

    // 1つのアイコンは多くのユーザーに関連付けられる
    public function users()
    {
        return $this->hasMany(User::class, 'icon_id');
    }
}
