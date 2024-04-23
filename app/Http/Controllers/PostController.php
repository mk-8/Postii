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

class PostController extends Controller
{
	
	public function showCreateForm(){
		return view('/create-post');
	}

	public function storeNewPost(Request $request){
		$incomingFields = $request->validate([
			'title' => 'required',
			'body' => 'required',
			'postImage' => 'image|max:10000', //max-size allowed is 10MB
			'selected_category' => 'required'
		]);

		$user = auth()->user();
		if ($request->hasFile('post-image')) {
			$filename = $user->id . '-' . uniqid() . '.jpg';
			$imgData = \Image::make($request->file('post-image'))->encode('jpg');
			Storage::put('public/postImages/' . $filename, $imgData->encode());
			$incomingFields['postImage'] = $filename;
		}

		$incomingFields['title'] = strip_tags($incomingFields['title']);
		$incomingFields['body'] = strip_tags($incomingFields['body']);
		$incomingFields['user_id'] = auth()->id();
		$incomingFields['category'] = $request->input('selected_category');
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
		$post['body'] = Str::markdown($post->body);// we are usign markdown on the body of the post
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
		$post->likes()->delete();
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
