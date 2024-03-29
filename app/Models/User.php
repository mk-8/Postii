<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'username',
        'email',
        'password',
    ];

    protected function avatar(): Attribute{            // this function check whether user has updated avatar or not. If not it assign the default avatar
        return Attribute::make(get: function($value){
            return $value ? '/storage/avatars/' . $value : '/fallback-avatar.jpg';
        });
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];


    public function posts(){
        return $this->hasMany(Post::class, 'user_id');  //user has many posts
    }

    public function followers(){
        return $this->hasMany(Follow::class, 'followeduser');   //user has many follows
    }

    public function followingTheseUsers(){
        return $this->hasMany(Follow::class, 'user_id');      // user has many follows
    }

    public function feedPosts(){
        return $this->hasManyThrough(Post::class, Follow::class, 'user_id', 'user_id', 'id', 'followeduser');
    }

    public function likes(){
        return $this->hasMany(Like::class);
    }

    public function geolocation(){
        return $this->hasOne(Geolocation::class);
    }

    public function comments(){
        return $this->hasMany(Comment::class);
    }
}
