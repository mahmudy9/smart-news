<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    public function user()
    {
        return $this->belongsTo('App\User' , 'user_id');
    }


    public function reports()
    {
        return $this->hasMany('App\Report' , 'comment_id');
    }


    public function replies()
    {
        return $this->hasMany('App\Reply' , 'comment_id');
    }


    public function commentlikes()
    {
        return $this->hasMany('App\Commentlike' , 'comment_id');
    }


    public function article()
    {
        return $this->belongsTo('App\Article' , 'article_id');
    }

    
}
