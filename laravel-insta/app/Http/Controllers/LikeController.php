<?php

namespace App\Http\Controllers;

use App\Models\Like;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LikeController extends Controller
{
    private $like;

    public function __construct(Like $like){
        $this->like = $like;
    }

    # This method will store the like into likes table
    public function store($post_id){
        $this->like->user_id = Auth::user()->id; //the id of the user who like the post
        $this->like->post_id = $post_id; //the id of the post being liked
        $this->like->save(); //save to the database

        return redirect()->back(); //return the view to the same page
    }

    # This method will destroy/delete the likes from the likes table
    public function destroy($post_id){
        $this->like
            ->where('user_id', Auth::user()->id)
            ->where('post_id', $post_id)
            ->delete();
            // The where() method is going to search for the user_id and the post_id field of
            // in the likes table the delete it if it find these ids

            return redirect()->back();


    }
}
