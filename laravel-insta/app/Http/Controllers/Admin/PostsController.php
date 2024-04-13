<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Post;

class PostsController extends Controller
{
    private $post;

    public function __construct(Post $post){
        $this->post = $post;
    }

    public function index(){
        //the same as : "SELECT *FROM posts ORDER BY created_at DESC LIMIT 5"
        $all_posts = this->post->latest()->paginate(5);

        //Got to admin/posts/index.blade.php with the data from $all_posts
        return view('admin.post.index')->with('all_posts',$all_posts);
    }
}
