<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Follow extends Model
{
    use HasFactory;

    # Indicate to the model that we will
    # going to use the timestamps
    public $timestamps = false;

    # Use this method to get the info of the follower
    public function follower(){
        return $this->belongsTo(User::class, 'follower_id');
    }

    # use this method to get the info of the user being followed
    public function following(){
        return $this->belongsTo(User::class, 'following_id')->withTrashed();
    }
}
