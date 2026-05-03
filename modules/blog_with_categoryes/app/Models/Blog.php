<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\SlugName;
use App\Traits\OrderSort;

class Blog extends Model
{
    use SlugName, OrderSort;

    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function categories()
    {
        return $this->belongsToMany(BlogCategory::class,'categories_blogs','blog_id','category_id');
    }

    public function image()
    {
        return $this->morphOne(Image::class,'object');
    }

    public function editCategory($aNewCatId)
    {
        $relation = $this->categories();
        $aOldCatId = $relation->pluck('blog_categories.id')->toArray();
        $ids_to_delete = array_diff($aOldCatId,$aNewCatId);
        $ids_to_add = array_diff($aNewCatId,$aOldCatId);
        $relation->detach($ids_to_delete);
        $relation->attach($ids_to_add);
    }
}
