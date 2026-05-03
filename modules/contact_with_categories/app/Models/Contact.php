<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\OrderSort;

class Contact extends Model
{
    use OrderSort;

    public function category()
    {
        return $this->belongsTo(ContactCategories::class);
    }
}
