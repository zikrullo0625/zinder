<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Chat extends Model
{
    protected $fillable = [
        'last_message_id',
    ];

    public function users()
    {
        return $this->belongsToMany(User::class, 'chat_user', 'chat_id', 'user_id')->withTimestamps();
    }

    public function messages()
    {
        return $this->hasMany(Message::class);
    }
}
