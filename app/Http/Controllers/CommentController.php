<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    public function addComment(Request $request){
        $incomingFields = $request->validate([     //perform validation on all the data coming from the form
            'content' => ['required']
        ]);

        
        $incomingFields['content'] = strip_tags($incomingFields['content']);
        $newComment = new Comment;
        $newComment->content = $incomingFields['content'];
        $newComment->user_id = auth()->id();
        $newComment->post_id = $request->input('post_id');

        $post_id = $request->input('post_id');

        if (!$post_id) {
            return redirect()->back()->with('error', 'Invalid post ID');
        }

        $newComment->save();

        return redirect("/post/{$post_id}")->with('success', 'Added a comment.');

    }

    public function delete(Comment $comment){
        $comment->delete();
        return redirect()->back()->with('success', 'Comment deleted');   
    }
    
}
