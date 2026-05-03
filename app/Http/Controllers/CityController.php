<?php

namespace App\Http\Controllers;

use App\Http\Requests\CityRequiredRequest;
use App\Models\City;
use App\Services\CityService;
use Illuminate\Http\Request;

class CityController extends Controller
{
    public $cityService;

    public function __construct(CityService $cityService)
    {
        $this->cityService = $cityService;
    }

    public function getList()
    {
        $cities = City::orderBy('order', 'asc')->get();
        $result = $this->cityService->getList($cities);
        return response()->json([
            'success' => true,
            'data' => $result,
        ]);
    }

    public function required(CityRequiredRequest $request)
    {
        $this->cityService->required($request->user(),$request->name);
        return response()->json([
            'success' => true,
            'data' => [],
        ]);
    }
}
