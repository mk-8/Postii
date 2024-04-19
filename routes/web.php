<?php

use App\Events\ChatMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\FollowController;
use App\Http\Controllers\CommentController;

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

Route::get('/admins-only', function(){
    return "Only admins are allowed";
})->middleware('can:visitAdminPages');

//Users related routes
Route::get('/', [UserController::class, 'showCorrectHomepage'])->name('login');  //named this route as login route, so that laravel sends it to this route
Route::post('/register',[UserController::class, 'register'])->middleware('guest');
Route::post('/login',[UserController::class, 'login'])->middleware('guest');
Route::post('/logout',[UserController::class, 'logout'])->middleware('auth');
Route::get('/manage-info', [UserController::class, 'showInfoForm'])->middleware('auth');
Route::post('/manage-info', [UserController::class, 'storeInfo'])->middleware('auth');


//Follow related routed
Route::post('/create-follow/{user:username}', [FollowController::class, 'createFollow'])->middleware('auth');
Route::post('/remove-follow/{user:username}', [FollowController::class, 'removeFollow'])->middleware('auth');

//Posts related routes
Route::get('/create-post', [PostController::class, 'showCreateForm'])->middleware('auth');   //used middleware, so that only the users which have logged in can create posts
Route::post('/create-post', [PostController::class, 'storeNewPost'])->middleware('auth');
Route::get('/post/{post}', [PostController::class, 'viewSinglePost']);
Route::delete('/post/{post}', [PostController::class, 'delete'])->middleware('can:delete,post');
Route::get('/post/{post}/edit', [PostController::class, 'showEditForm'])->middleware('can:update,post');
Route::put('/post/{post}', [PostController::class, 'actuallyUpdate'])->middleware('can:update,post');
Route::get('/search/{term}', [PostController::class , 'search']);

//like related routes
Route::post('/like', [LikeController::class, 'like'])->middleware('auth');
Route::get('/like/count/{postId}', [LikeController::class, 'getLikeCount']);

//comment related routes
Route::post('/usercomment', [CommentController::class, 'addComment'])->middleware('auth');
Route::delete('/comment/{comment}', [CommentController::class, 'delete'])->middleware('auth');

//Profile related routes
Route::get('/profile/{user:username}',[UserController::class, 'profile']);
Route::get('/profile/{user:username}/followers',[UserController::class, 'profileFollowers']);
Route::get('/profile/{user:username}/following',[UserController::class, 'profileFollowing']);

//Using middleware to cache
Route::middleware('cache.headers:public;max_age=20;etag')->group(function(){

    Route::get('/profile/{user:username}/raw',[UserController::class, 'profileRaw']);
    Route::get('/profile/{user:username}/followers/raw',[UserController::class, 'profileFollowersRaw']);
    Route::get('/profile/{user:username}/following/raw',[UserController::class, 'profileFollowingRaw']);
    
});
//Chat realted routes
Route::post('/send-chat-message', function(Request $request){
    $formFields = $request->validate([
        'textvalue' => 'required'
    ]);

    if(!trim(strip_tags($formFields['textvalue']))){
        return response()->noContent();
    }

    broadcast(new ChatMessage(['username' => auth()->user()->username, 'textvalue' => strip_tags($request->textvalue), 'avatar' => auth()->user()->avatar]))->toOthers();
    return response()->noContent();
})->middleware('auth');

//password reset routes
Route::get('/forgotPassword', [UserController::class, 'showforgotPassword']);
Route::post('/forgotPassword', [UserController::class, 'forgotPassword']);
Route::get('/resetPassword', [UserController::class, 'showResetPassword']);
Route::post('/resetPassword', [UserController::class, 'resetPassword']);
