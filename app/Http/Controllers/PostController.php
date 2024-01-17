<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Jobs\SendNewPostEmail;
use Illuminate\Support\Facades\Mail;

class PostController extends Controller
{
    public function showCreateForm(){
        return view('/create-post');
    }

    public function storeNewPost(Request $request){
        $incomingFields = $request->validate([
            'title' => 'required',
            'body' => 'required'
        ]);
        
        $incomingFields['title'] = strip_tags($incomingFields['title']);
        $incomingFields['body'] = strip_tags($incomingFields['body']);
        $incomingFields['user_id'] = auth()->id();

        $newPost = Post::create($incomingFields);

        //send the email into the queue so that there is no waiting to display the post
        dispatch(new SendNewPostEmail(['sendTo' => auth()->user()->email, 'name' => auth()->user()->username, 'title' => $newPost->title]));

        return redirect("/post/{$newPost->id}")->with('success', 'New post successfully created.');
    }

    public function viewSinglePost(Post $post){
        $ourHTML = Str::markdown($post->body);
        $post['body'] = $ourHTML; // we are usign markdown on the body of the post
        return view('single-post', ['post' => $post]);  //get a hold of the post and pass it to the view as an associative array
    }

    public function delete(Post $post){
        $post->delete();
        return redirect('/profile/' . auth()->user()->username)->with('success', 'Post successfully deleted');
    }

    public function showEditForm(Post $post){
        return view('edit-post', ['post' => $post]);
    }

    public function actuallyUpdate(Post $post, Request $request){
        $incomingFields = $request->validate([
            'title' => 'required',
            'body' => 'required'
        ]);

        $incomingFields['title'] = strip_tags($incomingFields['title']);
        $incomingFields['body'] = strip_tags($incomingFields['body']);
        
        $post->update($incomingFields);

        return back()->with('success', 'Post successfully updated');
    }

    public function search($term){
        $posts = Post::search($term)->get();
        $posts->load(['user' => function ($query) {
            $query->select('id', 'username', 'avatar');
        }]);
        return $posts;
    }

    public function storeNewPostApi(Request $request){
        $incomingFields = $request->validate([
            'title' => 'required',
            'body' => 'required'
        ]);
        
        $incomingFields['title'] = strip_tags($incomingFields['title']);
        $incomingFields['body'] = strip_tags($incomingFields['body']);
        $incomingFields['user_id'] = auth()->id();

        $newPost = Post::create($incomingFields);

        //send the email into the queue so that there is no waiting to display the post
        dispatch(new SendNewPostEmail(['sendTo' => auth()->user()->email, 'name' => auth()->user()->username, 'title' => $newPost->title]));

        return $newPost->id;
    }

    public function deleteApi(Post $post){
        $post->delete();
        return "true";
    }
}
