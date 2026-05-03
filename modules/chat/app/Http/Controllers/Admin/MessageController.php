<?php
/**
 * Created by Velgir
 */

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\Dialog;
use App\Models\User;
use App\Models\Message;

class MessageController extends Controller
{
    public function __construct()
    {
    }

    public function AdminIndex(Request $oRequest)
    {
        if(!$oRequest->input("sText"))
            $oModels = Message::orderBy("id", "desc")
                ->with("dialog","author")
                ->get();
        if($oRequest->ajax())
            return response()->json($oModels);
        else
            return view("admin.message.index",[
                    "oModels" => $oModels,
            ]);
    }

    public function AdminCreate()
    {
        $oDialogs = Dialog::all();
        $oUsers = User::all();
        return view("admin.message.form"
           ,["oDialogs"=>$oDialogs,"oUsers"=>$oUsers,]
        );
    }

    public function AdminEdit(Message $oModel)
    {
        $oDialogs = Dialog::all();
        $oUsers = User::all();
        return view("admin.message.form"
            ,["oModel"=>$oModel,"oDialogs"=>$oDialogs,"oUsers"=>$oUsers,]
        );
    }

    public function AdminStore(Request $oRequest, Message $oModel)
    {
        $this->validate($oRequest, [
            "text"=>"required|string",
            "file"	=> "file|mimes:pdf,txt,jpeg,png,gif",
            "dialog_id"=> "required|integer|exists:dialogs,id",
            "author_id"=> "required|integer|exists:users,id",
        ]);
        if($oRequest->input("id")){
            $oModel = Message::find($oRequest->input("id"));
        }
        if($oRequest->hasFile("file")){
            $oFile = $oRequest->file("file");
            $sPath = 'uploads/dialog_'.$oRequest->input("dialog_id").'/files';
            $oModel->file_name = $oFile->getClientOriginalName();
            $aNameParts = explode('.',$oModel->file_name);
            $ext = array_pop($aNameParts);
            $oModel->file_path = $oFile->storeAs($sPath,str_replace(' ','_',$oModel->file_name).'.'.$ext,['disk'=>'public']);
        }
        $oModel->fill($oRequest->all());
        $oModel->save();
        return redirect()->route('admin_message_')->with("success", "Запись успешно создана!");
    }

    public function AdminDelete(Message $oModel)
    {
        $oModel->delete();
        if(!request()->ajax())
            return redirect(request()->headers->get('referer'))->with("success", "Запись успешно удалена!");
        else
            return json_encode(["status"=>"OK","message"=>"Запись успешно удалена!"]);
    }
}