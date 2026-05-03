<?php

namespace App\Http\Controllers;

use App\Models\ExchangeRate;
use Illuminate\Http\Request;

class TestController extends Controller
{

    public function getList()
    {
        $exchangeRates = ExchangeRate::get();
        return response()->json([
            'data' => $exchangeRates->toArray(),
        ]);
    }

}
