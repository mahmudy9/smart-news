<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Bookmark extends Model
{
    public function user()
    {
        return $this->belongsTo('App\User' , 'user_id');
    }


    public function article()
    {
        return $this->belongsTo('App\Article' , 'article_id');
    }
}
