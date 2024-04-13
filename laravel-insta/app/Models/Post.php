<?php

namespace App\Models;

use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Post extends Model
{
    use HasFactory;

    # Use this method to get the owner of the post
    public function user()
    {
        # This will be use later on to identify
        # the owner of the post
        return $this->belongsTo(User::class);->withTrashed();
        //user (User.php) --it is user model
      
    }

    # Use this method to get the categories under a post
    public function categoryPost(){
        return $this->hasMany(CategoryPost::class);
    }

    # Use this method to get all comments of the post
    // One to many relationship
    // With this method, if the post has many comments
    // then, we can use this method to display all the
    // comments of the post
    public function comments(){
        return $this->hasMany(Comment::class);
    }

    # One to many relationship
    # Use this method to the likes of the post
    public function likes(){
        return $this->hasMany(Like::class);
    }

    # This method will return TRUE if the user already liked the post
    public function isLiked(){
        return $this->likes()->where('user_id', Auth::user()->id)->exists();
    }
}
