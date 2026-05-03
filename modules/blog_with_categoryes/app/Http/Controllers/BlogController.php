<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    public function index()
    {
        $oModels = Blog::where('published',1)->with('image','categories')->get();
        return view('blog.index',['oModels'=>$oModels]);
    }

    public function view(Blog $oModel)
    {
        return view('blog.view',['oModel'=>$oModel]);
    }
}
