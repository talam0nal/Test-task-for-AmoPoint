<?php

namespace App\Http\Controllers;

use App\Models\StaticPage;
use Illuminate\Http\Request;

class StaticPageController extends Controller
{
    public function view(Request $oRequest,$oModel='')
    {
        $user = $oRequest->user();
        $bIsAdmin = $user !==null && $user->is_admin==1;
        $bIsEdited = $oRequest->get('edit',false) && $bIsAdmin;
        $oModel = StaticPage::where('slug','=',$oModel)->first();
        if(!$oModel || ( !$bIsAdmin && $oModel->published != 1))
            abort(404);
        return view('static_page.view',['oModel'=>$oModel,'bIsEdited'=>$bIsEdited]);
    }
}
