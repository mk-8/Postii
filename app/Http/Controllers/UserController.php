<?php

namespace App\Http\Controllers;

use Image;
use App\Models\Post;
// use Intervention\Image\Image;
use App\Models\User;
// use Intervention\Image\Image;
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

            // dd($user->IP,$user->latitude, $user->longitude );

            // $position = Location::get($ip);
            // $lat1 = $position->latitude;
            // $lon1 = $position->longitude;

            // $position1 = Location::get('117.247.52.179');
            // $lat2 = $position1->latitude;
            // $lon2 = $position1->longitude;
            // dd($position1);
            // dd($lat1,$lon1,$lat2,$lon2);

            // ---------------------
            // $earthRadius = 6371;

            // $lat1 = deg2rad($lat1);
            // $lon1 = deg2rad($lon1);
            // $lat2 = deg2rad($lat2);
            // $lon2 = deg2rad($lon2);

            // $deltaLat = $lat2 - $lat1;
            // $deltaLon = $lon2 - $lon1;
            // $a = sin($deltaLat / 2) * sin($deltaLat / 2) +
            //     cos($lat1) * cos($lat2) * sin($deltaLon / 2) * sin($deltaLon / 2);
            // $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
            // $distance = $earthRadius * $c;

            // dd($distance); 

            // ---------------------
            
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

    // public function showCorrectHomepage(){

    //     if (auth()->check()){
    //         return view('homepage-feed', ['posts' => auth()->user()->feedPosts()->latest()->paginate(4)]);
    //     }

    //     else {
    //         $postCount = Cache::remember('postCount', 20, function (){
    //             sleep(5);
    //             return Post::count();
    //         });
    //         return view('homepage', ['postCount' => $postCount]);
    //     }
    // }

    public function showCorrectHomepage() {
        if (Auth::check()) {
            $user = Auth::user();

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


            return view('homepage-feed', compact('followingPosts', 'postsWithin300km', 'postsGreater2000km'));
        } else {
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

               //resize the image with intervetion/image package
        Storage::put('public/avatars/' . $filename, $imgData);   //store the image in the public folder

        $oldAvatar = $user->avatar;
        $user->avatar = $filename;         //storing the image as filename.jpg in avatar column in DB 
        $user->save();  

        if ($oldAvatar != "/fallback-avatar.jpg") {        // don't store every avatar, only the last updated
            Storage::delete(str_replace("/storage/","public/", $oldAvatar));
        }

        return back()->with('success' ,'Congratulations on the new avatar!');
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
