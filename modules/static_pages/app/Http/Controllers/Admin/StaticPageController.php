<?php

namespace App\Http\Controllers\Admin;

use App\Models\StaticPage;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class StaticPageController extends Controller
{
    public function __construct()
    {
    }

    public function AdminIndex(Request $oRequest)
    {
        if ($oRequest->ajax()) {
            $columns = $oRequest->input('columns',[]);
            $iTotalModels = StaticPage::query()->count();
            $oModelsRequest = StaticPage::query()->when($oRequest->filled('search.value'), function ($query) use ($oRequest) {
                $query
                    ->where('name', 'like', '%' . $oRequest->input('search.value') . '%')
                    ->orWhere('slug', 'like', '%' . $oRequest->input('search.value') . '%')
                    ->orWhere('seo_title', 'like', '%' . $oRequest->input('search.value') . '%')
                    ->orWhere('seo_keywords', 'like', '%' . $oRequest->input('search.value') . '%')
                    ->orWhere('seo_description', 'like', '%' . $oRequest->input('search.value') . '%');
            });
            $iFilteredModels = (clone $oModelsRequest)->count();
            $oModelsRequest->when($oRequest->filled('order'),function ($query) use ($oRequest,$columns) {
                foreach ($oRequest->input('order',[]) as $order) {
                    if(isset($columns[$order["column"]]['data'])){
                        $query->orderBy($columns[$order["column"]]['data'],$order["dir"]);
                    }
                }
            },function ($query){$query->orderBy('id', 'desc');});
            $aModels = $oModelsRequest
                ->limit($oRequest->input('length', 10))
                ->offset($oRequest->input('start', 0))
                ->get();
            return response()->json([
                'draw' => $oRequest->input('draw', 0),
                'recordsTotal'=> $iTotalModels,
                'recordsFiltered'=> $iFilteredModels,
                'data' => $aModels,
            ]);
        } else
            return view('admin.static_page.index', [
            ]);
    }

    public function AdminCreate()
    {
        return view('admin.static_page.form');
    }

    public function AdminEdit(StaticPage $oModel)
    {
        return view('admin.static_page.form',[
            'oModel'=>$oModel,
        ]);
    }

    public function AdminStore(Request $oRequest, StaticPage $oModel)
    {
        $this->validate($oRequest, [
            'name'		=> 'required|min:3|max:128',
            'text'		=> 'required',
            "seo_title"=>['string','nullable','max:255'],
            "seo_keywords"=>['string','nullable','max:255'],
            "seo_description"=>['string','nullable'],
            'slug'      => 'string|alpha_dash|nullable',
        ]);

        if($oRequest->input('id')){
            $oModel = StaticPage::find($oRequest->input('id'));
        }

        if(!$oRequest->input('slug',false))
            $oModel->slug = $oModel->generateSlug($oRequest->input('name'));
		else
		{
			if($oRequest->input('id')){
				if($oModel->slug != $oRequest->input('slug'))
					$oModel->slug = $oModel->adaptSlug($oRequest->input('slug'),$oRequest->input('id'));
			}
			else
				$oModel->slug = $oModel->adaptSlug($oRequest->input('slug'));
		}

        if(!$oModel->checkRoutes($oModel->slug))
            return redirect()->back()->withErrors(['slug'=>'Этот адрес уже используется. Попробуйте другой']);

        $oModel->fill($oRequest->except('slug'));
        $oModel->save();



        return redirect()->route('admin_static_page_')->with('success', 'Страница успешно создана!');
    }

    public function AdminPublic(Request $oRequest, StaticPage $oModel)
    {
        $aResult = ['скрыта','опубликована'];
        $oModel->published = !$oModel->published;
        $oModel->save();
        $sMessage = 'Страница ' .$oModel->name. ' успешно '. $aResult[$oModel->published] .'!';
        if(!$oRequest->ajax())
            return redirect(request()->headers->get('referer'))->with('success', $sMessage);
        else
            return json_encode(['status'=>'OK','message'=>$sMessage]);
    }

    public function AdminDelete(StaticPage $oModel)
    {
        $oModel->delete();
        if(!request()->ajax())
            return redirect(request()->headers->get('referer'))->with('success', 'Страница успешно удалена!');
        else
            return json_encode(['status'=>'OK','message'=>'Страница успешно удалена!']);
    }
}
