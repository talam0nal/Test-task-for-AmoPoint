<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\SlugName;

class PortfolioCategory extends Model
{
    use SlugName;

    public function portfolios()
    {
        return $this->belongsToMany(Portfolio::class,'categories_portfolios');
    }
}
