<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    public function keywords()
    {
        return $this->hasMany('App\Keyword' , 'article_id');
    }


    public function bookmarks()
    {
        return $this->hasMany('App\Bookmark' , 'article_id');
    }


    public function likes()
    {
        return $this->hasMany('App\Like' , 'article_id');
    }


    public function shares()
    {
        return $this->hasMany('App\Share' , 'article_id');
    }


    public function comments()
    {
        return $this->hasMany('App\Comment' , 'article_id');
    }


    public function category()
    {
        return $this->belongsTo('App\Category' , 'category_id');
    }


    public function reply()
    {
        return $this->hasMany('App\Reply' , 'article_id');
    }

}
