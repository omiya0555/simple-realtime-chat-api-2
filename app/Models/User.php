<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens; 

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasApiTokens;
    // Added the HasApiTokens after install the sanctum

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'icon_id',
    ];

    // 1人のユーザーは多くのメッセージを送信できる
    public function messages()
    {
        return $this->hasMany(Message::class, 'sender_id');
    }

    // 1人のユーザーは多くのチャットルームに参加できる
    public function chatRooms()
    {
        return $this->belongsToMany(ChatRoom::class, 'chat_room_users');
    }

    // ユーザーは1つのアイコンを持つ
    public function icon()
    {
        return $this->belongsTo(UserIcon::class, 'icon_id');
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
