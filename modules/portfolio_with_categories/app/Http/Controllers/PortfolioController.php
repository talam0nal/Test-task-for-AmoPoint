<?php

namespace App\Http\Controllers;

use App\Models\Portfolio;
use Illuminate\Http\Request;

class PortfolioController extends Controller
{
    public function index()
    {
        $oModels = Portfolio::where('published',1)->with('image','categories')->get();
        return view('portfolio.index',['oModels'=>$oModels]);
    }

    public function view(Portfolio $oModel)
    {
        return view('portfolio.view',['oModel'=>$oModel]);
    }
}
