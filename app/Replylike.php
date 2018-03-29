<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Replylike extends Model
{
    public function user()
    {
        return $this->belongsTo('App\User' , 'user_id');
    }


    public function reply()
    {
        return $this->belongsTo('App\Reply' , 'reply_id');
    }
}
