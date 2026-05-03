<?php

namespace App\Http\Controllers\Admin;

use App\Models\Contact;
use App\Models\ContactCategories;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Image;

class ContactController extends Controller
{
    public function __construct()
    {
    }

    public function AdminIndex(Request $oRequest)
    {

        if(!$oRequest->input('sText'))
            $oModels = Contact::orderBy('order', 'asc')->with('category')->paginate(10);
        else
            $oModels = Contact::where('name','like','%'.$oRequest->input('sText').'%')
                ->orWhere('value','like','%'.$oRequest->input('sText').'%')->with('category')
                ->orderBy('order', 'asc')->paginate(10);
        if($oRequest->ajax())
            return response()->json($oModels);
        else
            return view('admin.contact.index',[
                'oModels' => $oModels,
            ]);
    }

    public function AdminCreate()
    {
        $oCategories = ContactCategories::where('published',1)->get();
        $iContactCount = Contact::count()+1;
        return view('admin.contact.form',['oCategories'=>$oCategories,'aExistCatId'=>[],'iContactCount'=>$iContactCount]);
    }

    public function AdminEdit(Contact $oModel)
    {
        $oCategories = ContactCategories::all();
        return view('admin.contact.form',[
            'oCategories'=>$oCategories,
            'oModel'=>$oModel,
        ]);
    }

    public function AdminStore(Request $oRequest, Contact $oModel)
    {
        //dd($oRequest);
        $this->validate($oRequest, [
            'name'		=> 'required|min:3|max:128',
            'value'	    => 'required|min:3|max:128',
            'category_id'=> 'required|integer|exists:contact_categories,id',
            'order' => 'required|integer',
        ]);

        if($oRequest->input('id')){
            $oModel = Contact::find($oRequest->input('id'));
        }

        $oModel->name = $oRequest->input('name');
        $oModel->value = $oRequest->input('value');
        $oModel->category_id = $oRequest->input('category_id');
        $oModel->setOrder($oRequest->input('order'));
        $oModel->save();
        return redirect()->route('admin_contact_')->with('success', 'Контакт успешно создано!');
    }

    public function AdminPublic(Request $oRequest, Contact $oModel)
    {
        $aResult = ['скрыт','опубликован'];
        $oModel->published = !$oModel->published;
        $oModel->save();
        $sMessage = 'Контакт ' .$oModel->name. ' успешно '. $aResult[$oModel->published] .'!';
        if(!$oRequest->ajax())
            return redirect(request()->headers->get('referer'))->with('success', $sMessage);
        else
            return json_encode(['status'=>'OK','message'=>$sMessage]);
    }

    public function AdminDelete(Contact $oModel)
    {
        $oModel->delete();
        if(!request()->ajax())
            return redirect(request()->headers->get('referer'))->with('success', 'Портфолио успешно удалено!');
        else
            return json_encode(['status'=>'OK','message'=>'Портфолио успешно удалено!']);
    }
}
