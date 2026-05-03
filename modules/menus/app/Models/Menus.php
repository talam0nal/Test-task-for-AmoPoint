<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\OrderSort;

/**
 * @property string $name
 * @property string $link
 * @property int $type
 * @property int $order
 * @property int $active
 */
class Menus extends Model
{
	use OrderSort;

	protected $table = 'menus';

	public $timestamps = false;

    protected $fillable = [
        'name', 'link', 'type', 'order',
    ];

    public static function initMenusByType($forse=false)
    {
        global $rstmenus;
        if(!$rstmenus||$forse)
        {
            $items = self::where('active',1)->orderBy('order')->get();
            $result=[];
            foreach ($items as $item)
                $result[$item->type][]=$item;
            $rstmenus = $result;
        }
        return $rstmenus;
    }

    public static function getMenusByType($type=0): array
    {
        $menus = self::initMenusByType();
        return $menus[$type] ?? [];
        //return self::where('type',$type)->where('active',1)->orderBy('order')->get();
    }
}
