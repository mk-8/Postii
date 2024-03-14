<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Geolocation extends Model
{
    use HasFactory;

    protected $fillable = [
        'post_id',
        'user_id',
        'IP',
        'City',
        'State',
        'Country',
        'Latitude',
        'Longitude',
        'zipcode'
    ];

    public function user(){
        return $this->belongsTo(User::class, 'user_id');  
    }

    public function post(){
        return $this->belongsTo(Post::class, 'post_id');
    }



}
