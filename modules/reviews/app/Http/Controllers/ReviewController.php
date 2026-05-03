<?php

namespace App\Http\Controllers;

use App\Models\Review;
use Illuminate\Http\Request;
use App\Models\Image;

class ReviewController extends Controller
{
    public function index()
    {
        $oModels = Review::where('published',1)->with('image','author','author.main_image')->get();
        return view('review.index',['oModels'=>$oModels]);
    }

    public function store(Request $oRequest)
    {
        //dd($oRequest);
        if(auth()->check())
            $this->validate($oRequest, [
                'text'		=> 'required',
            ]);
        else
            $this->validate($oRequest, [
                'name'		=> 'required|string|min:3|max:128|nullable',
                'text'		=> 'required',
                'preview'	=> 'image|mimes:jpeg,jpg,png',
            ]);
        $oModel = new Review();
        $PreviewPath = false;
        $oModel->text = $oRequest->input('text');
        if(auth()->check())   //если передан юзер-автор, устанавливаем его и не работаем с именем и картикой
            $oModel->user_id = auth()->id();
        else    //если автор не указан, устанавливаем аватарку и имя (если есть) из переданных значений
        {
            if($oRequest->input('name',false))
                $oModel->name = $oRequest->input('name');
            //если это новое портфолио (процесс создания) то подготавливаем дефолтную картинку
            if(!$oRequest->input('id'))
            {
                $oImage = public_path('admin/img/default.jpg');
            }
            //если превью передавали - то берем его данные
            if($oRequest->hasFile('preview')){
                $oImage = $oRequest->file('preview');
            }
            //если какое-либо изображение загружено сохраняем и получаем путь

            if(isset($oImage))
                $PreviewPath = Image::cropImage($oImage,$oRequest->input('crop',NULL),'review');
        }
        $oModel->save();
        if(!empty($PreviewPath))
        {
            $oModel->image()->create(['path'=>$PreviewPath,'is_main'=>1,'order'=>1]);
        }
        return redirect('/reviews');
    }
}
