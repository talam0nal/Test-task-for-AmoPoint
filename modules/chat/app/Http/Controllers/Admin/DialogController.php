<?php
/**
 * Created by Velgir
 */

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\User;
use App\Models\Dialog;

class DialogController extends Controller
{
    public function __construct()
    {
    }

    public function AdminIndex(Request $oRequest)
    {
        if(!$oRequest->input("sText"))
            $oModels = Dialog::orderBy("id", "desc")
                ->with("sender","reader")
                ->get();
        if($oRequest->ajax())
            return response()->json($oModels);
        else
            return view("admin.dialog.index",[
                    "oModels" => $oModels,
            ]);
    }

    public function AdminCreate()
    {
        $oUsers = User::all();
        return view("admin.dialog.form"
           ,["oUsers"=>$oUsers,]
        );
    }

    public function AdminEdit(Dialog $oModel)
    {
        $oUsers = User::all();
        return view("admin.dialog.form"
            ,["oModel"=>$oModel,"oUsers"=>$oUsers,]
        );
    }

    public function AdminStore(Request $oRequest, Dialog $oModel)
    {
        $this->validate($oRequest, [
            "last_message"=>"nullable",
            "sender_id"=> "required|integer|exists:users,id",
            "reader_id"=> "required|integer|exists:users,id",
        ]);
        if($oRequest->input("id")){
            $oModel = Dialog::find($oRequest->input("id"));
        }
        $oModel->fill($oRequest->all());
        $oModel->save();
        return redirect()->route('admin_dialog_')->with("success", "Запись успешно создана!");
    }

    public function AdminPublic(Request $oRequest, Dialog $oModel)
    {
        $aResult = ["скрыта","опубликована"];
        $oModel->published = !$oModel->published;
        $oModel->save();
        $sMessage = "Запись успешно ". $aResult[$oModel->published] ."!";
        if(!$oRequest->ajax())
            return redirect(request()->headers->get('referer'))->with("success", $sMessage);
        else
            return json_encode(["status"=>"OK","message"=>$sMessage]);
    }

    public function AdminDelete(Dialog $oModel)
    {
        $oModel->delete();
        if(!request()->ajax())
            return redirect(request()->headers->get('referer'))->with("success", "Запись успешно удалена!");
        else
            return json_encode(["status"=>"OK","message"=>"Запись успешно удалена!"]);
    }
}