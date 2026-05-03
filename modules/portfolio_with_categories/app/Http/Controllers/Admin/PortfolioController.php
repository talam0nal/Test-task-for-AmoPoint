<?php

namespace App\Http\Controllers\Admin;

use App\Models\Portfolio;
use App\Models\PortfolioCategory;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Image;

class PortfolioController extends Controller
{
	public function __construct()
	{
	}

	public function AdminIndex(Request $oRequest)
	{

		if(!$oRequest->input('sText'))
			$oModels = Portfolio::orderBy('order', 'asc')->with('image','categories')->get();
		else
			$oModels = Portfolio::where('name','like','%'.$oRequest->input('sText').'%')->with('image','categories')
				->orderBy('order', 'asc')->get();
		if($oRequest->ajax())
			return response()->json($oModels);
		else
			return view('admin.portfolio.index',[
				'oModels' => $oModels,
			]);
	}

	public function AdminCreate()
	{
		//dd(response());
		$oCategories = PortfolioCategory::where('published',1)->get();
		$iPortfolioCount = Portfolio::count()+1;
		return view('admin.portfolio.form',['oCategories'=>$oCategories,'aExistCatId'=>[],'iPortfolioCount'=>$iPortfolioCount]);
	}

	public function AdminEdit(Portfolio $oModel)
	{
		//dd(response());
		$oCategories = PortfolioCategory::all();
		return view('admin.portfolio.form',[
			'oCategories'=>$oCategories,
			'oModel'=>$oModel,
			'aExistCatId'=>$oModel->categories()->pluck('portfolio_categories.id')->toArray(),
		]);
	}

	public function AdminStore(Request $oRequest, Portfolio $oModel)
	{
		//dd($oRequest);
		$this->validate($oRequest, [
			'name'		=> 'required|min:3|max:128',
			'text'	=> 'required',
			'preview'	=> 'image|mimes:jpeg,jpg,png',
			'slug'      => 'string|alpha_dash|nullable',
			'order'     => 'required|integer',
			'categories'=> 'required|array',
			'categories.*'=>'integer|exists:portfolio_categories,id',

			'images'    => 'array|nullable',
			'images.*'  => 'image|mimes:jpeg,jpg,png',
		]);

		if($oRequest->input('id')){
			$oModel = Portfolio::find($oRequest->input('id'));
		}
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
		$PreviewPath = false;
		if(isset($oImage))
			$PreviewPath = Image::cropImage($oImage,$oRequest->input('crop',NULL),'portfolio', 500, 500);

		if(!$oRequest->input('slug',false))
			$oModel->slug = $oModel->generateSlug($oRequest->input('name'));
		else
			$oModel->slug = $oModel->adaptSlug($oRequest->input('slug'));

		$oModel->name = $oRequest->input('name');
		$oModel->content = $oRequest->input('text');
		$oModel->setOrder($oRequest->input('order'));
		$oModel->save();
		if(!empty($PreviewPath))
		{
			if($oImage = $oModel->image)
				$oImage->remove();
			$oModel->images()->create(['path'=>$PreviewPath,'is_main'=>1,'order'=>1]);
		}
		$oModel->editCategory($oRequest->input('categories'));
		//$oPortfolio->categories()->attach($oRequest->input('cotegories'));

		//Присваиваем загруженные изображения модели
		$aImagesID = $oRequest->input('images_id');
		if($aImagesID) {
			Image::massAssign($oRequest->input('images_id'), $oModel->id);
		} else {
			$aImagesID = [];
		}
		$aModelImages = [];
		foreach ($oModel->images as $oImage){
			if($oImage->is_main == false)
				$aModelImages[] = $oImage->id;
		}
		$aModelImages = array_merge($aModelImages, $aImagesID);
		$aModelImages = array_diff($aModelImages, array_diff_assoc($aModelImages, array_unique($aModelImages)));
		if(!empty($aModelImages))
			Image::massDelete(implode(',',$aModelImages));

		return redirect()->route('admin_portfolio_')->with('success', 'Блог успешно создано!');
	}

	public function AdminPublic(Request $oRequest, Portfolio $oModel)
	{
		$aResult = ['скрыто','опубликовано'];
		$oModel->published = !$oModel->published;
		$oModel->save();
		$sMessage = 'Портфолио ' .$oModel->name. ' успешно '. $aResult[$oModel->published] .'!';
		if(!$oRequest->ajax())
			return redirect(request()->headers->get('referer'))->with('success', $sMessage);
		else
			return json_encode(['status'=>'OK','message'=>$sMessage]);
	}

	public function AdminDelete(Portfolio $oModel)
	{
		$aImagesId = $oModel->images()->pluck('images.id')->toArray();
		Image::massDelete(implode(',',$aImagesId));
		//$oPortfolio->fields()->delete();
		//$oModel->images()->delete();
		$oModel->categories()->detach();
		$oModel->delete();
		if(!request()->ajax())
			return redirect(request()->headers->get('referer'))->with('success', 'Портфолио успешно удалено!');
		else
			return json_encode(['status'=>'OK','message'=>'Портфолио успешно удалено!']);
	}
}
