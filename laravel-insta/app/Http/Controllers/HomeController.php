<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Post; //this is the Post.php (model)
use App\Models\User; //this is the User.php (model)

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */

    private $post; //this will become the object of our Post.php (Model)
    private $user; //this will become the object of our User.php (Model)

    public function __construct(Post $post, User $user)
    {
        // $this->middleware('auth');

        $this->post = $post;
        $this->user = $user;
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $suggested_users = $this->getSuggestedUsers();
        $home_posts = $this->getHomePosts();
        //$all_posts = $this->post->latest()->get();
        // The same as "SELECT * FROM posts ORDER BY created_at DESC";

        return view('users.home')
            ->with('home_posts', $home_posts)
            ->with('suggested_users', $suggested_users);
        //opens the home.blade.php and carry the data ($all_post) with it
    }

    public function getHomePosts(){
        $all_posts = $this->post->latest()->get();
        $home_posts = [];
        // In case the $home_posts is empty, it will not return NULL, but empty instead

        foreach ($all_posts as $post) {
            if ($post->user->isFollowed() || $post->user->id === Auth::user()->id) {
                $home_posts[] = $post;
            }
        }

        return $home_posts;
    }

    //This method will retrieved all the users
    //that the Auth user is not following yet
    private function getSuggestedUsers(){
        $all_users = $this->user->all()->except(Auth::user()->id);
        # "SELECT * FROM users"

        $suggested_users = [];
        # This array will be use to store the users
        # that are not yet being followed by the AUTH user

        foreach ($all_users as $user) {
            if (!$user->isFollowed()) {
                $suggested_users[] = $user;
            }
        }
        // return $suggested_users; //100 users, 5 users
        return array_slice($suggested_users, 0, 5);
        # $suggested_users["David Monroe", "Tim Watson" "Test User"];
        # array_slice(x, y, z)
        # x --- array
        # y --- offset/starting index
        # z --- length / how many
    }
}
