<?php

namespace App\Http\Controllers\Admin;

use App\Models\Review;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Image;

class ReviewController extends Controller
{
    public function __construct()
    {
    }

    public function AdminIndex(Request $oRequest)
    {

        if(!$oRequest->input('sText'))
            $oModels = Review::orderBy('id', 'desc')->with('image','author','author.main_image')->paginate(10);
        else
            $oModels = Review::where('name','like','%'.$oRequest->input('sText').'%')
                ->orWhere('name','like','%'.$oRequest->input('sText').'%')->with('image','author','author.main_image')
                ->orderBy('id', 'desc')->paginate(10);
        if($oRequest->ajax())
            return response()->json($oModels);
        else
            return view('admin.review.index',[
                'oModels' => $oModels,
            ]);
    }

    public function AdminCreate()
    {
        $oUsers = User::where('active',1)->get();
        return view('admin.review.form',['oUsers'=>$oUsers]);
    }

    public function AdminEdit(Review $oModel)
    {
        $oUsers = User::all();
        return view('admin.review.form',[
            'oModel'=>$oModel,
            'oUsers'=>$oUsers
        ]);
    }

    public function AdminStore(Request $oRequest, Review $oModel)
    {
        //dd($oRequest);
        $this->validate($oRequest, [
            'name'		=> 'required_without:user_id|string|min:3|max:128|nullable',
            'text'		=> 'required',
            'preview'	=> 'image|mimes:jpeg,jpg,png',
            'user_id'   => 'integer|exists:users,id|nullable',
        ]);

        if($oRequest->input('id')){
            $oModel = Review::find($oRequest->input('id'));
        }
        $PreviewPath = false;
        $oModel->text = $oRequest->input('text');
        if($oRequest->input('user_id',false))   //если передан юзер-автор, устанавливаем его и не работаем с именем и картикой
            $oModel->user_id = $oRequest->input('user_id');
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
            if($oImage = $oModel->image)
                $oImage->remove();
            $oModel->image()->create(['path'=>$PreviewPath,'is_main'=>1,'order'=>1]);
        }
        return redirect()->route('admin_review_')->with('success', 'Отзыв успешно создано!');
    }

    public function AdminPublic(Request $oRequest, Review $oModel)
    {
        $aResult = ['скрыт','опубликован'];
        $oModel->published = !$oModel->published;
        $oModel->save();
        $sMessage = 'Отзыв ' .$oModel->name. ' успешно '. $aResult[$oModel->published] .'!';
        if(!$oRequest->ajax())
            return redirect(request()->headers->get('referer'))->with('success', $sMessage);
        else
            return json_encode(['status'=>'OK','message'=>$sMessage]);
    }

    public function AdminDelete(Review $oModel)
    {
        if($oImage = $oModel->image)
            $oImage->remove();
        //$oPortfolio->fields()->delete();
        //$oModel->images()->delete();
        //$oModel->categories()->detach();
        $oModel->delete();
        if(!request()->ajax())
            return redirect(request()->headers->get('referer'))->with('success', 'Отзыв успешно удален!');
        else
            return json_encode(['status'=>'OK','message'=>'Отзыв успешно удален!']);
    }
}
