<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Follow;
use Illuminate\Http\Request;

class FollowController extends Controller
{
    
    public function createFollow(User $user){
        //cannot follow yourself
        if ($user->id == auth()->user()->id){
            return back()->with('failure', 'You cannot follow yourself');
        }
        //cannot follow, you are following
        $existCheck = Follow::where([['user_id', '=', auth()->user()->id], ['followeduser', '=', $user->id]])->count();

        if ($existCheck){
            return back()->with('failure','You are already following that user');
        }

        $newFollow = new Follow;
        $newFollow->user_id = auth()->user()->id;   //user_id followed user {x}
        $newFollow->followeduser = $user->id;
        $newFollow->save();

        return back()->with('success', 'User successfully followed');

    }

    public function removeFollow(User $user){
        Follow::where([['user_id', '=', auth()->user()->id],['followeduser','=',$user->id]])->delete();
        return back()->with('success', 'User successfully unfollowed.');
    }
}
