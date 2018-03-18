<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ChatRoom extends Model
{
    protected $table = 'chat_rooms';

    protected $fillable = ['created_by', 'name', 'image',' is_private', 'link'];

    public function messages()
    {
        return $this->hasMany('App\Message', 'chat_room_id', 'id');
    }
}
