<?php

namespace App\Http\Controllers;

use App\Models\StaticPage;
use Illuminate\Http\Request;

class StaticPageController extends Controller
{
    public function index()
    {
        $oModels = StaticPage::where('published',1)->get();
        return view('static_page.index',['oModels'=>$oModels]);
    }

    public function view(Request $oRequest, StaticPage $oModel)
    {
        $user = $oRequest->user();
        $bIsAdmin = $user !==null && $user->is_admin==1;
        if(!$oModel || ( !$bIsAdmin && $oModel->published != 1))
            abort(404);
        return view('static_page.view',['oModel'=>$oModel]);
    }
}
