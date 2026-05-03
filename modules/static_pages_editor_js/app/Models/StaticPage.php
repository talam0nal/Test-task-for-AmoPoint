<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\SlugName;
use Illuminate\Support\Carbon;
/**
 * @property string $name
 * @property string $slug
 * @property string $seo_title
 * @property string $seo_keywords
 * @property string $seo_description
 * @property string $text
 * @property int $published
 * @property Carbon $created_at
 * @property Carbon $updated_at
*/
class StaticPage extends Model
{
    use SlugName;

    protected $fillable = [
        'name',
        'slug',
        'seo_title',
        'seo_keywords',
        'seo_description',
        'text',
        'published',
    ];

    public function getRouteKeyName()
    {
        return 'id';
    }

}
