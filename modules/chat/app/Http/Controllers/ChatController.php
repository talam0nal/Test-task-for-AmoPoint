<?php

namespace App\Http\Controllers;

use App\Models\Dialog;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    public function downloadMessageFile(Message $oMessage)
    {
        return response()->download(public_path($oMessage->file_path));
    }

    public function sendMessage(Request $oRequest)
    {
        $this->validate($oRequest,[
            'reader_id' => 'nullable|exists:users,id',
            'dialog_id' => 'nullable|exists:dialogs,id',
            'text' => 'nullable|string',
            'file' => 'file|mimes:pdf,txt,jpeg,png,gif'
        ]);



        if(Auth::check())
        {
            if(!empty($oRequest->input('reader_id')))  //if we start dialog - target should be teacher or admin - not simple user
            {
                $oReader = User::find($oRequest->input('reader_id'));
                if($oReader->is_seller==1)
                {
                    $oDialog = new Dialog();
                    $oDialog->sender_id = Auth::id();
                    $oDialog->reader_id = $oRequest->input('reader_id');
                    //$oDialog->auth = Auth::check()?1:0;
                    $oDialog->published = 1;
                    $oDialog->save();
                }
                else
                    return json_encode(['status'=>'error','message'=>'wrong reader']);
            }
            else
                $oDialog = Dialog::find($oRequest->input('dialog_id'));
            if($oDialog->published==1)
            {
                $oNewMessage = new Message();
                $oNewMessage->author_id = Auth::id();
                if($oRequest->has('file'))
                {
                    $oFile = $oRequest->file("file");
                    $sPath = 'uploads/dialogs/dialog_'.$oRequest->input("dialog_id").'/files';
                    $oNewMessage->file_name = $oFile->getClientOriginalName();
                    $aNameParts = explode('.',$oNewMessage->file_name);
                    $ext = array_pop($aNameParts);
                    $oNewMessage->file_path = $oFile->storeAs($sPath,str_replace(' ','_',implode('.',$aNameParts)).'.'.$ext,['disk'=>'common']);
                    $oNewMessage->text = $oNewMessage->file_name;
                }
                else
                    $oNewMessage->text = $oRequest->input("text");

                $oMessage = $oDialog->messages()->save($oNewMessage);
                $oDialog->last_message = $oMessage->created_at;
                $oDialog->save();
                return json_encode(['status'=>'ok','data'=>[
                    'message' => $oMessage,
                    'file_link'=>!empty($oMessage->file_path)?route('download',['oMessage'=>$oMessage]):''
                ]]);
            }
            else
                return json_encode(['status'=>'error','message'=>'dialog blocked']);
        }
        else
            return json_encode(['status'=>'error','message'=>'auth required']);
    }

    public function getDialog(Request $oRequest)
    {
        $this->validate($oRequest,[
            'dialog_id' => 'required|exists:dialogs,id',
            'offset' => 'nullable|integer|min:0',
        ]);
        $aMessages = Message::where('dialog_id',$oRequest->input('dialog_id'))->with('author')->orderBy('created_at','desc')
            ->when($oRequest->has('offset')  && !empty($oRequest->input('offset')),function($query) use ($oRequest){
                return $query->skip($oRequest->input('offset'));
            })
            ->take(10)->get()->toArray();
        $aMessages = array_reverse($aMessages);
        $aResult = [];
        foreach($aMessages as $oMessage)
        {
            $aResult[] = [
                'text' => $oMessage['text'],
                'file_name' => !empty($oMessage['file_name'])?$oMessage['file_name']:'',
                'file_path' => !empty($oMessage['file_path'])?$oMessage['file_path']:'',
                'author_id' => $oMessage['author_id'],
                'file_download' => '/download/'.$oMessage['id'],
            ];
        }
        return json_encode(['status'=>'ok','data'=>$aResult]);
    }

    public function ban(Request $oRequest)
    {
        $this->validate($oRequest,[
            'dialog_id' => 'required|exists:dialogs,id',
        ]);
        $oDialog = Dialog::find($oRequest->input('dialog_id'));
        if(!empty($oDialog) && $oDialog->published==1 && ($oDialog->sender_id==Auth::id() || $oDialog->reader_id==Auth::id()))
        {
            $oDialog->published=0;
            $oDialog->save();
            if($oDialog->sender_id==Auth::id())
                $oOtherUser = $oDialog->reader;
            else
                $oOtherUser = $oDialog->sender;
            if($oOtherUser->is_admin!=1)
            {
                if($oRequest->ajax())
                    return response('ok');
                else
                    return redirect()->back()->with('success','Пользователь заблокирован. Вы больше не сможете с ним общаться или проводить торговые операции.');
            }
        }
    }
}
