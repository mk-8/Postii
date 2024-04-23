<?php

namespace App\Http\Controllers;

use Image;
use App\Models\Post;
use App\Models\User;
use App\Models\Follow;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Events\OurExampleEvent;
use Illuminate\Validation\Rule;
use App\Mail\ForgotPasswordMail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Redirect;
use Stevebauman\Location\Facades\Location;

class UserController extends Controller
{

	public function getIP() {
		$clientIP = request()->ip();
		return $clientIP;
	}

	public function register(Request $request){
		$incomingFields = $request->validate([     //perform validation on all the data coming from the form
			'username' => ['required','min:3', 'max:20', Rule::unique('users','username')],  //('tableName', 'field')
			'email' => ['required', 'email',Rule::unique('users','email')],
			'password' => ['required','confirmed'],
			'password_confirmation' => 'required'
		]);

		$user = User::create($incomingFields); // using create method on User model to create a new record
		auth()->login($user);
		return redirect('/')->with('success','Thank you for registering.');
	}

	public function login(Request $request){
		$incomingFields = $request->validate([
			'loginusername' => 'required',
			'loginpassword' => 'required'
		]);

		if (auth()->attempt(['username' => $incomingFields['loginusername'], 'password' => $incomingFields['loginpassword']])) 
		{
			$request->session()->regenerate(); //manually regenerate the session ID
			event(new OurExampleEvent(['username' => auth()->user()->username, 'action' => 'login']));
			
			$user = Auth::user();
			// $user->IP = $this->getIP();
			$user->IP = '117.97.174.167';
			
			// $ip = $this->getIP();
			$ip = '117.97.174.167';
			$position = Location::get($ip);
			$user->latitude = $position->latitude;
			$user->longitude = $position->longitude;

			$user->save();
			return redirect('/')->with('success','You have successfully logged in.'); //redirect here with the message
		}
		else
		return redirect('/')->with('failure','Invalid credentials.');

	}

	public function logout(){
		event(new OurExampleEvent(['username' => auth()->user()->username, 'action' => 'logout']));
		auth()->logout();
		return redirect('/')->with('success','You have successfully logged out.');
	}

	public function showCorrectHomepage(Request $request) {
		if (Auth::check()) {
			$user = Auth::user();
			
			$selectedCategory = $request->input('category');
			// Retrieve latest posts from users the current user follows
			$followingPosts = $user->feedPosts()->latest()->paginate(8);

			// Retrieve the user's latitude and longitude
			$userLat = $user->latitude;
			$userLong = $user->longitude;

			// Retrieve posts from users within 300km
			$postsWithin300km = Post::whereHas('geolocation', function ($query) use ($userLat, $userLong) {
					$query->selectRaw('(6371 * acos(cos(radians(?)) * cos(radians(latitude)) * cos(radians(longitude) - radians(?)) + sin(radians(?)) * sin(radians(latitude)))) AS distance', [$userLat, $userLong, $userLat])
						->having('distance', '<', 300);
				})
				->whereDoesntHave('user.followers', function ($query) use ($user) {
					$query->where('user_id', $user->id);
				})
				->where('user_id', '!=', auth()->id())
				->get();

			$userLat = $user->latitude;
			$userLong = $user->longitude;
			
			$postsGreater2000km = Post::whereHas('geolocation', function ($query) use ($userLat, $userLong) {
				$query->selectRaw('(6371 * acos(cos(radians(?)) * cos(radians(latitude)) * cos(radians(longitude) - radians(?)) + sin(radians(?)) * sin(radians(latitude)))) AS distance', [$userLat, $userLong, $userLat])
					->having('distance', '>', 2000);
				})
				->whereDoesntHave('user.followers', function ($query) use ($user) {
					$query->where('user_id', $user->id);
				})
			->where('user_id', '!=', auth()->id())
			->get();

			 $categoryPosts = collect();


			if ($selectedCategory) {
				$categoryPosts = Post::where('category', $selectedCategory)->get();
			} else {
				$categoryPosts = collect(); // Empty collection if no category is selected
			}

			return view('homepage-feed', compact('followingPosts', 'postsWithin300km', 'postsGreater2000km', 'categoryPosts','selectedCategory'));
		} 
		
		else {
			$postCount = Cache::remember('postCount', 20, function () {
				sleep(5);
				return Post::count();
			});

			return view('homepage', compact('postCount'));
		}
	}

	private function getSharedData($user){
		$currentlyFollowing = 0;
		if (auth()->check()){
			$currentlyFollowing = Follow::where([['user_id', '=', auth()->user()->id],['followeduser','=',$user->id]])->count();
		}

		View::share('sharedData', ['username' => $user->username,
									  'postCount' => $user->posts()->count(),
									  'avatar' => $user->avatar,
									  'currentlyFollowing' => $currentlyFollowing,
									  'followerCount' => $user->followers()->count(),
									  'followingCount' => $user->followingTheseUsers()->count()]);
	}

	public function profile(User $user){
		$this->getSharedData($user);    
		return view('profile-posts', ['posts' => $user->posts()->latest()->get()]);   
	}

	//Creating this raw method for SPA(Single Page Application) Technique
	public function profileRaw(User $user){
		return response()->json(['theHTML' => view('profile-posts-only', ['posts' => $user->posts()->latest()->get()])->render(), 'docTitle' => $user->username . "'s profile"]);
	}

	public function profileFollowers(User $user){
		$this->getSharedData($user);
		return view('profile-followers', ['followers' => $user->followers()->latest()->get()]);   
	}

	//Creating this raw method for SPA(Single Page Application) Technique
	public function profileFollowersRaw(User $user){
		return response()->json(['theHTML' => view('profile-followers-only', ['followers' => $user->followers()->latest()->get()])->render(), 'docTitle' => $user->username . "'s followers"]);
	}


	public function profileFollowing(User $user){
		$this->getSharedData($user);    
		return view('profile-following', ['following' => $user->followingTheseUsers()->latest()->get()]);   
	}

	//Creating this raw method for SPA(Single Page Application) Technique
	public function profileFollowingRaw(User $user){
		return response()->json(['theHTML' => view('profile-following-only', ['following' => $user->followingTheseUsers()->latest()->get()])->render(), 'docTitle' => "who " . $user->username . " follows"]);
	}

	public function showInfoForm(){
		$user = auth()->user();
		return view('manage-info', [
			'userData' => $user
		]);
	}

	public function showUpdateInfoForm(){
		$user = auth()->user();
		return view('update-info', [
			'userData' => $user
		]);
	}

	public function storeInfo(Request $request){
		$request->validate([
			'avatar' => 'required|image|max:10000', // Max size allowed is 10MB
			'dob' => 'required|date',
			'university' => 'required|string',
			'country' => 'required|string',
			'state' => 'required|string',
			'city' => 'required|string',
			'profession' => 'required|string'
		]);

		$user = auth()->user(); // Getting the authenticated user

		// Handle avatar upload
		if ($request->hasFile('avatar')) {
			$avatar = $request->file('avatar');
			$filename = $user->id . '-' . uniqid() . '.' . $avatar->getClientOriginalExtension();
			Storage::putFileAs('public/avatars', $avatar, $filename);
			$oldAvatar = $user->avatar;
			$user->avatar = $filename;
			if ($oldAvatar != "avatars/fallback-avatar.jpg") {
				Storage::delete('public/' . $oldAvatar);
			}
		}

		// Update other user information
		$user->dob = $request->input('dob');
		$user->university = $request->input('university');
		$user->country = $request->input('country');
		$user->state = $request->input('state');
		$user->city = $request->input('city');
		$user->profession = $request->input('profession');
		$user->update();

		return back()->with('success', 'Information added successfully!');
	}

	
	public function UpdateInfo(Request $request){
		$request->validate([
			'avatar' => 'nullable|image|max:10000', // Max size allowed is 10MB
			'dob' => 'required|date',
			'university' => 'required|string',
			'country' => 'required|string',
			'state' => 'required|string',
			'city' => 'required|string',
			'profession' => 'required|string'
		]);

		$user = auth()->user(); // Getting the authenticated user

		// Handle avatar upload
		if ($request->hasFile('avatar')) {
			$avatar = $request->file('avatar');
			$filename = $user->id . '-' . uniqid() . '.' . $avatar->getClientOriginalExtension();
			Storage::putFileAs('public/avatars', $avatar, $filename);
			$oldAvatar = $user->avatar;
			$user->avatar = $filename;
			if ($oldAvatar != "avatars/fallback-avatar.jpg") {
				Storage::delete('public/' . $oldAvatar);
			}
		}

		// Update other user information
		$user->dob = $request->input('dob');
		$user->university = $request->input('university');
		$user->country = $request->input('country');
		$user->state = $request->input('state');
		$user->city = $request->input('city');
		$user->profession = $request->input('profession');

		$user->save(); // Save the changes

		return back()->with('success', 'Information updated successfully!');
	}


	public function showforgotPassword(){
		return view('forgot-password');
	}

	public function forgotPassword(Request $request){

		$incomingFields = $request->validate([
			'email' => ['required','email']
		]);

		$password = Str::password(8,true,true,true,false);


		if($incomingFields['email'] == DB::Table('users')->where('email', '=', $incomingFields['email'])->value('email')){
			$newPassword = $password;
			DB::table('users')
				->where('email', $incomingFields['email'])
				->update(['password' => bcrypt($newPassword)]);    
			Session::flash('success', 'Email sent!'); 
			Mail::to($incomingFields['email'])->send(new ForgotPasswordMail([
				'password' => $newPassword,
			]));
			return back();
		}
		else{
			return back()->with('failure', 'Incorrect email!');
		}

	}

	public function showresetPassword(){
		return view('reset-password');
	}

	public function resetPassword(Request $request){
		$incomingFields = $request->validate([     
			'email' => ['required', 'email'],
			'oldPassword' => ['required'],
			'password' => ['required','confirmed'],
			'password_confirmation' => 'required'
		]);

		if($incomingFields['email'] == DB::Table('users')
			->where('email', '=', $incomingFields['email'])->value('email') 
			&& 
			Hash::check($incomingFields['oldPassword'], 
			DB::table('users')->where('email', $incomingFields['email'])
			->value('password')))
		{
			
			DB::table('users')
				->where('email', $incomingFields['email'])
				->update(['password' => bcrypt($incomingFields['password'])]);
			return redirect('/')->with('success', 'Password changed successfully');

		}

		else{
			return back()->with('failure', 'Email or Password is incorrect!');
		}



	}

	public function loginApi(Request $request){
		$incomingFields = $request->validate([
			'username' => 'required',
			'password' => 'required'
		]);

		if(auth()->attempt($incomingFields)){
			$user = User::where('username' , $incomingFields['username'])->first();
			$token = $user->createToken('ourapptoken')->plainTextToken;
			return $token;
		}
		return '';
	}

}
