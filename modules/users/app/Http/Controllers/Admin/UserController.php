<?php

namespace App\Http\Controllers\Admin;

use App\Models\Image;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function __construct()
    {
    }

    public function AdminIndex(Request $oRequest)
    {
        if ($oRequest->ajax()) {
            $columns = $oRequest->input('columns',[]);
            $iTotalModels = User::query()->count();
            $oModelsRequest = User::query()->when($oRequest->filled('search.value'), function ($query) use ($oRequest) {
                $query->where('name','like','%'.$oRequest->input('search.value').'%')
                    ->orWhere('surname','like','%'.$oRequest->input('search.value').'%')
                    ->orWhere('email','like','%'.$oRequest->input('search.value').'%');
            });
            $iFilteredModels = (clone $oModelsRequest)->count();
            $oModelsRequest->when($oRequest->filled('order'),function ($query) use ($oRequest,$columns) {
                foreach ($oRequest->input('order',[]) as $order) {
                    if(isset($columns[$order["column"]]['data'])){
                        $query->orderBy($columns[$order["column"]]['data'],$order["dir"]);
                    }
                }
            },function ($query){$query->orderBy("id", 'desc');});
            $aModels = $oModelsRequest
                ->with('main_image')
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
            return view('admin.user.index', [
            ]);
    }

    public function AdminEdit(User $oModel)
    {
        return view('admin.user.form',[
            'oModel'=>$oModel,
        ]);
    }

    public function AdminStore(Request $oRequest, User $oModel)
    {
        //dd($oRequest);
        if($oRequest->input('id')){
            $oModel = User::find($oRequest->input('id'));
        }

        $this->validate($oRequest, [
            'name'		=> ['required', 'string', 'alpha_dash', 'min:3', 'max:128'],
            'surname'	=> ['string', 'min:3', 'max:128', 'nullable'],
            'email'     => ['required', 'string', 'email', $oRequest->input('id')?Rule::unique('users','email')->ignore($oModel->id):Rule::unique('users','email')],
            'is_admin'  => ['required', 'in:1,0'],
            'preview'	=> ['image', 'mimes:jpeg,jpg,png'],
        ]);

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
            $PreviewPath = Image::cropImage($oImage,$oRequest->input('crop',NULL),'user');

        $oModel->fill($oRequest->all());
        $oModel->is_admin = $oRequest->input('is_admin');
        $oModel->save();
        if(!empty($PreviewPath))
        {
            if($oImage = $oModel->main_image)
                $oImage->remove();
            $oModel->main_image()->create(['path'=>$PreviewPath,'is_main'=>1,'order'=>1]);
        }
        return redirect()->route('admin_user_')->with('success', 'Пользователь успешно создано!');
    }

    public function AdminPublic(Request $oRequest, User $oModel)
    {
        $aResult = ['забанен','разбанен'];
        $oModel->active = !$oModel->active;
        $oModel->save();
        $sMessage = 'Пользователь ' .$oModel->name. ' успешно '. $aResult[$oModel->active] .'!';
        if(!$oRequest->ajax())
            return redirect(request()->headers->get('referer'))->with('success', $sMessage);
        else
            return json_encode(['status'=>'OK','message'=>$sMessage]);
    }

    public function AdminDelete(User $oModel)
    {
        if($oImage = $oModel->main_image)
            $oImage->remove();
        $oModel->delete();
        if(!request()->ajax())
            return redirect(request()->headers->get('referer'))->with('success', 'Пользователь успешно удален!');
        else
            return json_encode(['status'=>'OK','message'=>'Пользователь успешно удален!']);
    }
}
