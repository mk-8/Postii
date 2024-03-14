<?php

namespace App\Http\Controllers;

use App\Models\Like;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LikeController extends Controller{
    
   
    public function like(Request $request)
    {
    $postId = $request->input('post_id');

    // Validate the post_id input
    if (!$postId) {
        return redirect()->back()->with('error', 'Invalid post ID');
    }

    $post = Post::findOrFail($postId);

    // Check if the user has already liked the post
    $existingLike = Like::where('user_id', Auth::id())
        ->where('post_id', $postId)
        ->first();

    if ($existingLike) {
        // User has already liked the post, so remove the like
        $existingLike->delete();

        // Optionally, you can update the like count on the page using Blade
        $likeCount = Like::where('post_id', $postId)->count();

        return redirect()->back()->with('message', 'Post disliked successfully!')->with('likeCount', $likeCount);
    } else {
        // Create a new like record
        $like = new Like([
            'user_id' => Auth::id(),
            'post_id' => $postId,
        ]);

        $like->save();

        // Optionally, you can update the like count on the page using Blade
        $likeCount = Like::where('post_id', $postId)->count();

        return redirect()->back()->with('message', 'Post liked successfully!')->with('likeCount', $likeCount);
    }
}

    public function getLikeCount($postId)
    {
        $likeCount = Like::where('post_id', $postId)->count();
        return (string) $likeCount;
    }

}
