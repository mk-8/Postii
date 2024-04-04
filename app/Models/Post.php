<?php

namespace App\Models;

use Laravel\Scout\Searchable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Post extends Model
{
    use HasFactory;
    use Searchable;                 // laravel search function
    protected $fillable = [
        'title',
        'body',
        'user_id',
        'postImage'
    ];

    public function user(){
        return $this->belongsTo(User::class, 'user_id');  //post belongs to (user, relationship which is powered by) 
    }

    public function toSearchableArray(){        //it outputs the array which will show when something is found during search.
        return [
            'title' => $this->title,
            'body' => $this->body
        ];
    }

    public function likes()
    {
        return $this->hasMany(Like::class);
    }

    public function isLikedByUser($userId)
    {
        return $this->likes()->where('user_id', $userId)->exists();
    }

    public function geolocation(){
        return $this->hasOne(Geolocation::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

}
