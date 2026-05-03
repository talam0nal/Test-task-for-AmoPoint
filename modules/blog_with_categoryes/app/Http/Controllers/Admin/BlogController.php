<?php

namespace App\Http\Controllers\Admin;

use App\Models\Blog;
use App\Models\BlogCategory;
use App\Models\Image;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class BlogController extends Controller
{
    public function __construct()
    {
    }

    public function AdminIndex(Request $oRequest)
    {

        if(!$oRequest->input('sText'))
            $oModels = Blog::orderBy('order', 'asc')->with('image','categories')->get();
        else
            $oModels = Blog::where('name','like','%'.$oRequest->input('sText').'%')->with('image','categories')
                ->orderBy('order', 'asc')->get();
        if($oRequest->ajax())
            return response()->json($oModels);
        else
            return view('admin.blog.index',[
                'oModels' => $oModels,
            ]);
    }

    public function AdminCreate()
    {
        $oCategories = BlogCategory::where('published',1)->get();
        $iBlogCount = Blog::count()+1;
        return view('admin.blog.form',['oCategories'=>$oCategories,'aExistCatId'=>[],'iBlogCount'=>$iBlogCount]);
    }

    public function AdminEdit(Blog $oModel)
    {
        //dd(response());
        $oCategories = BlogCategory::all();
        return view('admin.blog.form',[
            'oCategories'=>$oCategories,
            'oModel'=>$oModel,
            'aExistCatId'=>$oModel->categories()->pluck('blog_categories.id')->toArray(),
        ]);
    }

    public function AdminStore(Request $oRequest, Blog $oModel)
    {
        //dd($oRequest);
        $this->validate($oRequest, [
            'name'		=> 'required|min:3|max:128',
            'text'		=> 'required',
            'preview'	=> 'image|mimes:jpeg,jpg,png',
            'slug'      => 'string|alpha_dash|nullable',
            'order'     => 'required|integer',
            'categories'=> 'required|array',
            'categories.*'=>'integer|exists:blog_categories,id'
        ]);

        if($oRequest->input('id')){
            $oModel = Blog::find($oRequest->input('id'));
        }
        //если это новая статья (процесс создания) то подготавливаем дефолтную картинку
        if(!$oRequest->input('id'))
        {
            $oImage = public_path('admin/img/default.jpg');
        }
        //если превью передавали - то берем его данные
        if($oRequest->hasFile('preview')){
            $oImage = $oRequest->file('preview');
        }
        //если какое-либо изображение загружено сохраняем и получаем путь
        $PreviewPath = false;
        if(isset($oImage))
            $PreviewPath = Image::cropImage($oImage,$oRequest->input('crop',NULL),'blog');

        if(!$oRequest->input('slug',false))
            $oModel->slug = $oModel->generateSlug($oRequest->input('name'));
        else
            $oModel->slug = $oModel->adaptSlug($oRequest->input('slug'));

        $oModel->name = $oRequest->input('name');
        $oModel->text = $oRequest->input('text');
        $oModel->setOrder($oRequest->input('order'));
        $oModel->save();
        if(!empty($PreviewPath))
        {
            if($oImage = $oModel->image)
                $oImage->remove();
            $oModel->image()->create(['path'=>$PreviewPath,'is_main'=>1,'order'=>1]);
        }
        $oModel->editCategory($oRequest->input('categories'));
        //$oPortfolio->categories()->attach($oRequest->input('cotegories'));

        //Если нужно приписать несколько дополнительных картинок
        /*if($oRequest->hasFile('images'))
        {
            $aImagesList = [];
            foreach($oRequest->file('images') as $iKey=>$oImage)
            {
                $oImageClass = new Image();
                $oImageClass->path = $oImageClass->cropImage($oImage,NULL,'blog');
                $oImageClass->is_main = 0;
                $oImageClass->order = $iKey+1;
                $aImagesList[] = $oImageClass;
            }
            $oModel->images()->saveMany($aImagesList);
        }
        if($oRequest->input('del_image_ids'))
            Image::massDelete($oRequest->input('del_image_ids'));*/
        return redirect()->route('admin_blog_')->with('success', 'Статья успешно создана!');
    }

    public function AdminPublic(Request $oRequest, Blog $oModel)
    {
        $aResult = ['скрыт','опубликован'];
        $oModel->published = !$oModel->published;
        $oModel->save();
        $sMessage = 'Блог ' .$oModel->name. ' успешно '. $aResult[$oModel->published] .'!';
        if(!$oRequest->ajax())
            return redirect(request()->headers->get('referer'))->with('success', $sMessage);
        else
            return json_encode(['status'=>'OK','message'=>$sMessage]);
    }

    public function AdminDelete(Blog $oModel)
    {
        if($oImage = $oModel->image)
            $oImage->remove();
        //$oPortfolio->fields()->delete();
        //$oModel->images()->delete();
        $oModel->categories()->detach();
        $oModel->delete();
        if(!request()->ajax())
            return redirect(request()->headers->get('referer'))->with('success', 'Статья успешно удалена!');
        else
            return json_encode(['status'=>'OK','message'=>'Статья успешно удалена!']);
    }
}
