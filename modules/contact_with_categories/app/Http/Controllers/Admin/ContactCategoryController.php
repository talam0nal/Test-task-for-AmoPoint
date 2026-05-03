<?php

namespace App\Http\Controllers\Admin;

use App\Models\ContactCategories;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ContactCategoryController extends Controller
{
    public function __construct()
    {
    }

    public function AdminCategory()
    {
        $oModels = ContactCategories::paginate(10);
        return view('admin.contact.category',['oModels'=>$oModels]);
    }

    public function AdminCategoryStore(Request $oRequest, ContactCategories $oModel)
    {
        $this->validate($oRequest, [
            'name' => 'required|string|max:128'
        ]);

        if($oRequest->input('id'))
            $oModel = ContactCategories::find($oRequest->input('id'));
        if($oRequest->input('name')!=$oModel->name)
        {
            $oModel->slug = $oModel->generateSlug($oRequest->input('name'));
        }

        $oModel->name = $oRequest->input('name');
        $oModel->save();
        return redirect()->route('admin_contact_category_')->with('success', 'Сохраненение выполнено');
    }

    public function AdminCategoryDelete(ContactCategories $oModel)
    {
        $oModel->delete();
        if(!request()->ajax())
            return redirect(request()->headers->get('referer'))->with('success', 'Категория успешно удалена!');
        else
            return json_encode(['status'=>'OK','message'=>'Категория успешно удалена!']);
    }

    public function AdminCategoryPublic(Request $oRequest, ContactCategories $oModel)
    {
        $aResult = ['скрыта','опубликована'];
        $oModel->published = !$oModel->published;
        $oModel->save();
        $sMessage = 'Категория ' . $oModel->name. ' успешно '. $aResult[$oModel->published] .'!';
        if(!$oRequest->ajax())
            return redirect(request()->headers->get('referer'))->with('success', $sMessage);
        else
            return json_encode(['status'=>'OK','message'=>$sMessage]);
    }
}
