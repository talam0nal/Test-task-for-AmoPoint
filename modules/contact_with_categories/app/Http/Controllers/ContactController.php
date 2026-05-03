<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function index()
    {
        $oModels = Contact::where('published',1)->with('category')->get();
        return view('contact.index',['oModels'=>$oModels]);
    }

    public function view(Contact $oModel)
    {
        return view('contact.view',['oModel'=>$oModel]);
    }
}
