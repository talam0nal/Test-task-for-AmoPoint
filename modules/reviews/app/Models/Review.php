<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    public function image()
    {
        return $this->morphOne(Image::class,'object');
    }

    public function author()
    {
        return $this->belongsTo(User::class,'user_id','id');
    }
}
