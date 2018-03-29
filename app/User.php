<?php

namespace App;

use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasApiTokens,Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'phone' , 'image'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token','active_code'
    ];



    public function hasRole($role)
    {
        if($this->roles()->where('name' , $role)->first())
        {
            return true;
        }

        return false;
    }



    public function roles()
    {
        return $this->belongsToMany('App\Role' , 'user_role' , 'user_id' , 'role_id');
    }


    public function shares()
    {
        return $this->hasMany('App\Share' , 'user_id');
    }


    public function searches()
    {
        return $this->hasMany('App\Search' , 'user_id');
    }

    
    
    public function reports()
    {
        return $this->hasMany('App\Report' , 'user_id');
    }


    public function replies()
    {
        return $this->hasMany('App\Reply' , 'user_id');
    }
    
    
    
    public function replylikes()
    {
        return $this->hasMany('App\Replylike' , 'user_id');
    }
    
    
    
    public function notifications()
    {
        return $this->hasMany('App\Notification' , 'user_id');
    }
    
    
    
    public function messages()
    {
        return $this->hasMany('App\Message' , 'user_id');
    }
    
    
    
    public function likes()
    {
        return $this->hasMany('App\Like' , 'user_id');
    }
    
    
    
    public function commentlikes()
    {
        return $this->hasMany('App\Commentlike' , 'user_id');
    }
    
    
    
    public function comments()
    {
        return $this->hasMany('App\Comment' , 'user_id');
    }
    
    
    
    public function bookmarks()
    {
        return $this->hasMany('App\Bookmark' , 'user_id');
    }



}
