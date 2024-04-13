<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; //authentication
use App\Models\User; //inherit the User model class

class ProfileController extends Controller
{
    private $user;

    public function __construct(User $user){
        $this->user = $user;
    }

    # Display the user details into show.blade.php
    # search for the user first
    public function show($id){
        $user = $this->user->findOrFail($id); //The same as "SELECT * FROM users WHERE id = $id"
        return view('users.profile.show')->with('user', $user); //display the data to show.blade.php
    }

    public function edit(){
        $user = $this->user->findOrFail(Auth::user()->id);
        return view('users.profile.edit')->with('user', $user);
    }

    public function update(Request $request){
        #1. Validate the first
        $request->validate([
            'name'   => 'required|min:1|max:50',
            'email'  => 'required|email|max:50|unique:users,email,' . Auth::user()->id,
            'avatar' => 'mimes:jpg,jpeg,gif,png|max:1048',
            'introduction' => 'max:100'
        ]);

        #2. Store the data to the database
        $user = $this->user->findOrFail(Auth::user()->id);
        //The same as "SELECT * FROM users WHERE id = Auth::user()->id" Note: The Auth::user()->id is the id of the currently logged in user
        $user->name = $request->name;
        $user->email = $request->email;
        $user->introduction = $request->introduction;

        //Check if the user uploaded an image
        if ($request->avatar) {
            $user->avatar = 'data:image/' . $request->avatar->extension() . ';base64,' . base64_encode(file_get_contents($request->avatar));
        }

        # Save to the Db
        $user->save();

        # Redirect
        return redirect()->route('profile.show', Auth::user()->id);
    }

    public function followers($id){
        $user = $this->user->findOrFail($id);
        return view('users.profile.followers')->with('user', $user);

        #Note: The $id is the id of the user that we want to view
        #      Any user can view anyone follower lists
    }

    public function following($id){
        $user = $this->user->findOrFail($id);
        return view('users.profile.following')->with('user', $user);
    }
}
