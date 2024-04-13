<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Follow; //inherit the follow model class

class FollowController extends Controller
{
    private $follow; //this will become the object of our model

    public function __construct(Follow $follow){
        // this " $this->follow " is our object
        $this->follow = $follow;
    }

    # This method will store the follower id and following id
    # into the follows table
    public function store($user_id){
        $this->follow->follower_id = Auth::user()->id; // the id of the follower (AUTH user)
        $this->follow->following_id = $user_id; // the id of the use being followed
        $this->follow->save(); //store the details into the follows table

        return redirect()->back(); //return the view to the same page
    }

    public function destroy($user_id){
        $this->follow
            ->where('follower_id', Auth::user()->id)
            ->where('following_id', $user_id)
            ->delete();

            return redirect()->back();
    }
}
