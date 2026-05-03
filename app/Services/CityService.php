<?php
/**
 * Created by PhpStorm.
 * User: TiberLex
 * Date: 18.08.2025
 * Time: 14:51
 */

namespace App\Services;


use App\Models\City;
use App\Models\CityRequired;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

class CityService
{
    public function get(City $city)
    {
        return [
            'id' => $city->id,
            'name' => $city->name,
        ];
    }

    public function getList(Collection $collection)
    {
        $result = [];
        foreach($collection as $item){
            $result[] = $this->get($item);
        }
        return $result;
    }

    public function required(User $user, string $city)
    {
        $cityRequest = CityRequired::query()->where('user_id',$user->id)->first();
        if(!$cityRequest){
            CityRequired::create(['name'=>$city,'user_id'=>$user->id]);
        } else {
            CityRequired::update(['name'=>$city]);
        }
    }
}