<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\Auth;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes; //inherit the SoftDeletes class so that we can use it

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

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
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    # Use this method to get the posts of a user
    # A user may have many posts
    # One to Many relationship
    public function posts(){
        return $this->hasMany(Post::class)->latest();
    }

    # One to many relationship ( hasMany() )
    # Use this method to get the followers of the user
    public function followers(){
        return $this->hasMany(Follow::class, 'following_id');
        #Note: to get the followers, we can select the following_id column from the Follow (follows table) model
    }

    # One to many relationship
    # To get all the users that the user is following
    public function following(){
        return $this->hasMany(Follow::class, 'follower_id');
        # Search the follower_id column with the ID to identify
        # the user that I am following
    }

    //This method will return either True of False
    public function isFollowed(){
        return $this->followers()->where('follower_id', Auth::user()->id)->exists();
        // Auth::user()->id -- is the follower -- it is the user who is currently logged in
        // First, get all the followers of the User ($this->followers()). Then from that lists,
        // we search for the Auth user from the follower column ('follower_id', Auth::user()->id)
    }
}
