<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\SlugName;

class BlogCategory extends Model
{
    use SlugName;

    public function blogs()
    {
        return $this->belongsToMany(Blog::class,'categories_blogs');
    }
}
