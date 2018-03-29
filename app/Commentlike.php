<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Commentlike extends Model
{
    public function user()
    {
        return $this->belongsTo('App\User' , 'user_id');
    }


    public function comment()
    {
        return $this->belongsTo('App\Comment' , 'comment_id');
    }
}
