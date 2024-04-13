<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class UsersController extends Controller
{
    private $user;

    public function __construct(User $user){
        $this->user = $user;
    }

    public function index(){
        //The withTrashed() method is going to retrieved the users who are 
        //softdeleted/deactivated
        $all_users = $this->user->withTrashed()->latest()->paginate(5);
        # The same as: "SELECT * FROM users ORDER BY created_at DESC"
        return view('admin.users.index')->with('all_users', $all_users);
    }

    public function deactivate($id){
         # The same as: "DELETE * FROM users WHERE id = $id"
        $this->user->destroy($id);
        return redirect()->back();//redirect the user to the same page

    }

    public function activate($id){
        $this->user->onlyTrashed()->findOrFail($id)->restore();
        #the onlyTrashed() is going to retrieved only the user who are soft deleted
        #The resotore() method is going to "un-delete" the deleted user, this mean that 
        #it will removed the value we have in the users table (deleted_at)
        return redirect()->back();
    }
}
