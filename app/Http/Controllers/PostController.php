<?php

namespace App\Http\Controllers;

use Image;
use App\Models\Post;
use App\Models\Geolocation;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Jobs\SendNewPostEmail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Stevebauman\Location\Facades\Location;

//new table for comments
//new table for geolocations

//everytime a post is shared. add the details such as ip address and other stuffs.

//in order to recommend a post using vicinity - algo, get the ip address of all the posts and 
// --feed in to the algorithm and check the distance. 

//1. see what other are upto in your area.
//2. curious about what people are doing in distant.


class PostController extends Controller
{
    public function showCreateForm(){
        return view('/create-post');
    }

    public function storeNewPost(Request $request){
        $incomingFields = $request->validate([
            'title' => 'required',
            'body' => 'required',
            'postImage' => 'image|max:10000' //max-size allowed is 10MB
        ]);

        $user = auth()->user();
        $filename = $user->id . '-' . uniqid() . '.jpg';
        $imgData = \Image::make($request->file('post-image'))->encode('jpg'); 
        Storage::put('public/postImages/' . $filename, $imgData->encode());

        $incomingFields['title'] = strip_tags($incomingFields['title']);
        $incomingFields['body'] = strip_tags($incomingFields['body']);
        $incomingFields['user_id'] = auth()->id();
        $incomingFields['postImage'] = $filename;

        $newPost = Post::create($incomingFields);


        
        // $ip = request()->ip();
        $ip = '117.97.174.167';

        $geoFields['postIP'] = $ip;
        $geoFields['user_id'] = auth()->id(); 
        $geoFields['post_id'] = $newPost->id;
        $geoFields['IP'] = $ip;
        
        
        $position = Location::get($ip); 
        $geoFields['City'] = $position->cityName;
        $geoFields['State'] = $position->regionName;
        $geoFields['Country'] = $position->countryName;
        $geoFields['Latitude'] = $position->latitude;
        $geoFields['Longitude'] = $position->longitude;
        $geoFields['zipcode'] = $position->zipCode;
        
        $newGeolocation = Geolocation::create($geoFields);

        //send the email into the queue so that there is no waiting to display the post
        dispatch(new SendNewPostEmail(['sendTo' => auth()->user()->email, 'name' => auth()->user()->username, 'title' => $newPost->title]));

        return redirect("/post/{$newPost->id}")->with('success', 'New post successfully created.');
    }

    public function viewSinglePost(Post $post){
        $ourHTML = Str::markdown($post->body);
        $post['body'] = $ourHTML; // we are usign markdown on the body of the post
        
        $postId = $post->id;
        $comments = DB::table('comments')
        ->join('users', 'comments.user_id', '=', 'users.id')
        ->select('comments.*', 'users.username', 'users.avatar')
        ->where('comments.post_id', '=', $postId)
        ->get();

        return view('single-post', ['post' => $post, 'comments' => $comments]);  //get a hold of the post and pass it to the view as an associative array
    }

    public function delete(Post $post){
        $post->geolocation()->delete();
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

    // public function showPosts(Request $request) {
    //     $user = auth()->user();
    //     $userLat = $user->latitude;
    //     $userLong = $user->longitude;

    //     // Retrieve latest posts from users the current user follows
    //     $followingPosts = $user->followings()->with('posts')->latest()->get()->pluck('posts')->flatten();

    //     // Retrieve posts from users within 300km whom the current user doesn't follow
    //     // $postsWithin300km = Post::whereNotIn('user_id', function($query) use ($user, $userLat, $userLong) {
    //     //     $query->select('users.id')
    //     //         ->from('users')
    //     //         ->join('geolocations', 'users.id', '=', 'geolocations.user_id')
    //     //         ->selectRaw('(6371 * acos(cos(radians(?)) * cos(radians(users.latitude)) * cos(radians(users.longitude) - radians(?)) + sin(radians(?)) * sin(radians(users.latitude)))) AS distance', [$userLat, $userLong, $userLat])
    //     //         ->where('distance', '<', 300)
    //     //         ->whereNotIn('users.id', function($innerQuery) use ($user) {
    //     //             $innerQuery->select('followeduser')
    //     //                 ->from('follows')
    //     //                 ->where('user_id', $user->id);
    //     //         });
    //     // })
    //     // ->get();

    //     return view('posts.index', compact('followingPosts'));
    // }

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
