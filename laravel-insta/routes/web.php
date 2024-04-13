<?php
# Users
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\FollowController;

# Admin
use App\Http\Controllers\Admin\UsersController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Auth::routes();

Route::group(['middleware' => 'auth'], function(){
    // This route is protected by auth middleware -- meaning, that no users
    // can access this route without logged-in
    // The '/' is the root URI --> equivalent to localhost or 127.0.0.1
    Route::get('/', [HomeController::class, 'index'])->name('index');

    // This route will open up the create.blade.php
    // This is the form use to create a post
    Route::get('/post/create', [PostController::class, 'create'])->name('create');

    // This route will call the store method in the postcontroller and store the post details to the posts table
    // The "{id}" -- is what we call "ROUTE PARAMETER", the purpose of this is to received the IDs of the post we are currently working on--- show post, edit/update the post, or maybe to delete post
    Route::post('/post/store', [PostController::class, 'store'])->name('post.store');
    Route::get('/post/{id}/show', [PostController::class, 'show'])->name('post.show');
    Route::get('/post/{id}/edit', [PostController::class, 'edit'])->name('post.edit');
    Route::patch('/post/{id}/update', [PostController::class, 'update'])->name('post.update');
    Route::delete('/post/{id}/destroy', [PostController::class, 'destroy'])->name('post.destroy');

    // Comments
    Route::post('/comment/{post_id}/store', [CommentController::class, 'store'])->name('comment.store');
    Route::delete('/destroy/{id}/destroy', [CommentController::class, 'destroy'])->name('comment.destroy');

    //Profile
    Route::get('/profile/{id}/show', [ProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile/update', [ProfileController::class, 'update'])->name('profile.update');

    //Like
    Route::post('/like/{post_id}/store', [LikeController::class, 'store'])->name('like.store');
    Route::delete('/like/{post_id}/destroy', [LikeController::class, 'destroy'])->name('like.destroy');

    //Follow
    Route::post('/follow/{user_id}/store', [FollowController::class, 'store'])->name('follow.store');
    Route::delete('/follow/{user_id}/destroy', [FollowController::class, 'destroy'])->name('follow.destroy');

    //Followers
    Route::get('/profile/{id}/followers', [ProfileController::class, 'followers'])->name('profile.followers');

    //Following
    Route::get('/profile/{id}/following', [ProfileController::class, 'following'])->name('profile.following');

    # Admin Routes
    Route::group(['prefix' => 'admin', 'as' => 'admin.'], function(){
        # Route for admin users
        Route::get('/users', [UsersController::class, 'index'])->name('users');
        # Note: We have the prefix " admin. ", this prefix will be appended into
        # name of the route, so final route name will be: admin.users

        Route::delete('/user/{id}/deactivate',[UsersController::class,'deactivate'])->name('user.deactivate');//admin.user.deactive
        Route::patch('/user/{id}/activate',[UsersController::class,'activate'])->name('user.activate');//admin.user.active--->Full route name
    });
});
