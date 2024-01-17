<?php

namespace App\Http\Controllers;

use Image;
use App\Models\Post;
// use Intervention\Image\Image;
use App\Models\User;
// use Intervention\Image\Image;
use App\Models\Follow;
use Illuminate\Http\Request;
use App\Events\OurExampleEvent;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Redirect;

class UserController extends Controller
{
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

    public function showCorrectHomepage(){

        if (auth()->check()){
            return view('homepage-feed', ['posts' => auth()->user()->feedPosts()->latest()->paginate(4)]);
        }

        else {
            $postCount = Cache::remember('postCount', 20, function (){
                sleep(5);
                return Post::count();
            });
            return view('homepage', ['postCount' => $postCount]);
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
        return response()->json(['theHTML' => view('profile-posts-only', ['posts' => $user->posts()->latest()->get()])->render(), 'docTitle' => $user->username . "'s Profile"]);
    }

    public function profileFollowers(User $user){
        $this->getSharedData($user);
        return view('profile-followers', ['followers' => $user->followers()->latest()->get()]);   
    }

    //Creating this raw method for SPA(Single Page Application) Technique
    public function profileFollowersRaw(User $user){
        return response()->json(['theHTML' => view('profile-followers-only', ['followers' => $user->followers()->latest()->get()])->render(), 'docTitle' => $user->username . "'s Followers"]);
    }


    public function profileFollowing(User $user){
        $this->getSharedData($user);    
        return view('profile-following', ['following' => $user->followingTheseUsers()->latest()->get()]);   
    }

    //Creating this raw method for SPA(Single Page Application) Technique
    public function profileFollowingRaw(User $user){
        return response()->json(['theHTML' => view('profile-following-only', ['following' => $user->followingTheseUsers()->latest()->get()])->render(), 'docTitle' => "Who " . $user->username . " Follows"]);
    }

    public function showAvatarForm(){
        return view('avatar-form');
    }

    public function storeAvatar(Request $request){
        $request->validate([
            'avatar' => 'required|image|max:10000'      //max-size allowed is 10MB
        ]);

        $user = auth()->user();   // getting a hold of user
        $filename = $user->id . '-' . uniqid() . '.jpg';   //generating unique usernames

        $imgData = \Image::make($request->file('avatar'))->fit(120)->encode('jpg');   //resize the image with intervetion/image package
        Storage::put('public/avatars/' . $filename, $imgData);   //store the image in the public folder

        $oldAvatar = $user->avatar;
        $user->avatar = $filename;         //storing the image as filename.jpg in avatar column in DB 
        $user->save();  

        if ($oldAvatar != "/fallback-avatar.jpg") {        // don't store every avatar, only the last updated
            Storage::delete(str_replace("/storage/","public/", $oldAvatar));
        }

        return back()->with('success' ,'Congratulations on the new avatar!');
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
