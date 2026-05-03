<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Menus;
use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;
use App\Models\Config;

class MenusController extends Controller
{
	protected $aMenuTypes =[0=>'Верхнее меню',1=>'Нижнее меню'];
	public function __construct()
	{
	}
    public function AdminIndex(Request $oRequest)
    {
        if ($oRequest->ajax()) {
            $columns = $oRequest->input('columns',[]);
            $iTotalModels = Menus::query()->count();
            $oModelsRequest = Menus::query()->when($oRequest->filled('search.value'), function ($query) use ($oRequest) {
                $query
                    ->where('name', 'like', '%' . $oRequest->input('search.value') . '%');
            });
            if($oRequest->filled('type'))
                $oModelsRequest->whereIn('type',$oRequest->input('type'));
            $iFilteredModels = (clone $oModelsRequest)->count();
            $oModelsRequest->when($oRequest->filled('order'),function ($query) use ($oRequest,$columns) {
                foreach ($oRequest->input('order',[]) as $order) {
                    if(isset($columns[$order["column"]]['data'])){
                        $query->orderBy($columns[$order["column"]]['data'],$order["dir"]);
                    }
                }
            },function ($query){$query->orderBy("order");});
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
            return view('admin.menus.index', [
                'types'=>$this->aMenuTypes,
            ]);
    }
	public function AdminCreate()
	{
		$iCount = Menus::count()+1;
		return view('admin.menus.form',['types'=>$this->aMenuTypes,'iCount'=>$iCount]);
	}

	public function AdminEdit(Menus $oModel)
	{
		return view('admin.menus.form',[
			'oModel'=>$oModel,
			'types'=>$this->aMenuTypes,
		]);
	}
	public function AdminStore(Request $oRequest, Menus $oModel)
	{
		$this->validate($oRequest, [
			'name'		=> 'required|min:3|max:255',
			'type'		=> 'required|integer',
			'order'		=> 'required|integer',
			'link'      => 'required|string',
		]);

		if($oRequest->input('id')){
			$oModel = Menus::find($oRequest->input('id'));
		}

		$oModel->fill($oRequest->except('order'));
		$oModel->setOrder($oRequest->input('order'));
		$oModel->save();

		return redirect(route('admin_menus_'))->with('success', ($oRequest->input('id'))?'Пункт успешно изменён!':'Пункт успешно создан!');
	}
	public function AdminPublic(Request $oRequest, Menus $oModel)
	{
		$aResult = ['скрыт','опубликован'];
		$oModel->active = !$oModel->active;
		$oModel->save();
		$sMessage = 'Пункт ' .$oModel->name. ' успешно '. $aResult[$oModel->active] .'!';
		if(!$oRequest->ajax())
			return redirect(request()->headers->get('referer'))->with('success', $sMessage);
		else
			return json_encode(['status'=>'OK','message'=>$sMessage]);
	}

	public function AdminDelete(Menus $oModel)
	{
		$oModel->delete();
		if(!request()->ajax())
			return redirect(request()->headers->get('referer'))->with('success', 'Пункт успешно удалён!');
		else
			return json_encode(['status'=>'OK','message'=>'Пункт успешно удалён!']);
	}
}

