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
        'user_id'
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
}
