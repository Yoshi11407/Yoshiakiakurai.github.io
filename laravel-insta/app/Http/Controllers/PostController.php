<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Post; // This is the Post.php -- our connection to the posts table
use App\Models\Category; //this is the Category.php -- our connection to the categories table

class PostController extends Controller
{
    # Note: When we are going to create a post, a category is required.

    private $category; // this will become the object of our Category model
    private $post; // this will become the object of our Post model

    public function __construct(Category $category, Post $post){
        # We will use '$this->category' object to select for a categories to use when a create a post
        $this->category = $category;

        # We will use '$this->post' object to insert data, read data, update data and delete data to the posts table
        $this->post = $post;
    }

    public function create(){
        //retrieved all categories from the categories table
        $all_categories = $this->category->all();
        //Display all categories to create.blade.php
        return view('users.posts.create')->with('all_categories',$all_categories);
    }

    // This method is use to insert post into posts table
    public function store(Request $request){
        #1. First, validata the data coming from the form.
        $request->validate([
            'category' =>'required|array|between:1,3',  // the category is required, should be of type array
            'description' => 'required|min:1|max:1000', // description is also required
            'image' => 'required|mimes:jpeg,jpg,png,gif|max:1048' //image is also required when creating a post
        ]);
        # Note: The error directive ( @error ) in create.blade.php will be invoke when there is an error in this validation

        #2. Next, Save the post details to the posts table
        $this->post->user_id     = Auth::user()->id; // the id of the user who is currently logged-in (the owner of the post)
        $this->post->image       = 'data:image/' . $request->image->extension() . ';base64,' . base64_encode(file_get_contents($request->image)); //the image converted into base64 encoding -- sequence of long characters will be save to the database
        $this->post->description = $request->description; // the description of the post coming from the create.blade.php form
        $this->post->save(); // The same as "INSERT INTO posts(`user_id`, `image`, `description`) VALUES('$this->post->user_id', '$this->post->image', '$this->post->description')"

        #3. Save the categories to the category_post table (PIVOT Table)
        foreach ($request->category as $category_id) {
            $category_post[] = ['category_id' => $category_id]; // temporarily save the selected ids of categories in the array
        }
        $this->post->categoryPost()->createMany($category_post); // insert selected category ids to the pivot table

        #4. Go back to homepage
        return redirect()->route('index');
    }

    //This method will received the $id coming from the route parameter (web.php)
    public function show($id){
        //search for the post using the post id from the posts table
        //and assign the result in $post property
        $post = $this->post->findOrFail($id);
        //$post = ['id' => 2, 'description' => 'sample post', 'user_id' => 1, 'created_at' => '10-10-2023', 'updated_at' => '10-10-2023'];

        //return the show.blade.php with the data in $post
        return view('users.posts.show')->with('post', $post);
    }

    public function edit($id){
        $post = $this->post->findOrFail($id);

        // If the AUTH USER is not the owner of the post, redirect to index page (homepage)
        //check if the id of the currently logged-in user is equal to the id of the user
        //who created the post
        if (Auth::user()->id != $post->user->id) {
            //If the id above is not equal, redirect the user to the homepage
            return redirect()->route('index'); //homepage
        }

        //Get all the categories from the categories table
        $all_categories = $this->category->all();
        # This is the same with "SELECT * FROM categories";

        //Get all the categories of this Post, save it in an array
        $selected_categories = [];//we will save the selected categories in this array
        foreach ($post->categoryPost as $category_post) {
            //for every category id in the post, save it in $selected_categories array
            $selected_categories[] = $category_post->category_id;
        }

        return view('users.posts.edit') //edit.blade.php
            ->with('post', $post)
            ->with('all_categories', $all_categories)
            ->with('selected_categories', $selected_categories);
    }

    public function update(Request $request, $id){
        # Step 1: validate the data first
        $request->validate([
            'category' => 'required|array|between:1,3',
            'description' => 'required|min:1|max:1000',
            'image' => 'mimes:jpeg,jpg,png,tif|max:1048'
        ]);

        # Step 2: Update the post
        $post = $this->post->findOrFail($id); //search for the post ID in the posts table
        $post->description = $request->description;


        # Step 3: Check if there is a NEW IMAGE uploaded
        // save the post to the posts table
        if ($request->image) { //if there is image in the image field of the form
            //which form are we referring in to? --- it is the form from the edit.blade.php
            $post->image = 'data:image/' . $request->image->extension() . ';base64,' . base64_encode(file_get_contents($request->image));
        }

        $post->save(); // Insert description, and the image now into post table
        // The same as "INSERT INTO posts(`description`, `image`) VALUES('$request->description', '$request->image')"

        # Step 4: Delete all the selected categories related to this post
        // We need to delete all of it, because we want to store the new lists
        // of categories
        $post->categoryPost()->delete();
        //this will delete the currently stored categories of the post
        //Use the relationship Post::categoryPost() to select the records related to this post
        // The code above is equivalent to "DELETE FROM posts WHERE id = $id"


        # Step 5: Save the new category lists into the category_post table
        foreach ($request->category as $category_id) {
            $category_post[] = ['category_id' => $category_id];
        }
        $post->categoryPost()->createMany($category_post);

        # Step 6: Redirect the user to the homepage
        return redirect()->route('post.show', $id);
    }

    public function destroy($id){
        $this->post->destroy($id);
        return redirect()->route('index');
    }
}
