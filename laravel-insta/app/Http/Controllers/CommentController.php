<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Comment; // Model Comment.php

class CommentController extends Controller
{
    #property
    private $comment; //this will become object of our Comment model

    #Constructor
    public function __construct(Comment $comment){
        $this->comment = $comment;
        // We will use the object ( $this->comment ) in every method/function
        // that we will have inside our comment controller
    }

    # use this method to insert the comments to the comments table
    public function store(Request $request, $post_id){
        #1. validate the data first
        $request->validate([
            'comment_body' . $post_id => 'required|max:150'
        ],
        [
            'comment_body' . $post_id . '.required' => 'You cannot submit an empty comment.',
            'comment_body' . $post_id . '.max' => 'The comment must not be greater than 150 characters.'
        ]);
        #2. Store the comment details to the table
        $this->comment->body = $request->input('comment_body' . $post_id);//actual comment
        $this->comment->user_id = Auth::user()->id; // id of the user who created the comment
        $this->comment->post_id = $post_id; //id of the post being commented
        $this->comment->save(); //insert to the table
        // This is the same with "INSERT INTO comments(`body`, `user_id`, `post_id`) VALUES('actual comment', 'Auth::user()->id', '$post_id')"

        return redirect()->back(); // redirect the user to the same page
    }

    public function destroy($id){
        $this->comment->destroy($id); //The same as "DELETE FROM comments WHERE id = $id"
        return redirect()->back(); // return the view of the user to the same page
    }
}
