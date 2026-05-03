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

    public function index()
    {
        $types = ['type1', 'type2', 'type3'];

        return view('test.form', compact('types'));
    }
}
