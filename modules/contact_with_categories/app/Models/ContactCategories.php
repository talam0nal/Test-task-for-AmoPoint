<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\SlugName;

class ContactCategories extends Model
{
    use SlugName;

    public function contacts()
    {
        return $this->hasMany(Portfolio::class);
    }
}
