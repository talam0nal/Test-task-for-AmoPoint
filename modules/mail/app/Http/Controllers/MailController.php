<?php

namespace App\Http\Controllers;

use App\Models\Mail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use PHPMailer\PHPMailer\Exception;
use App\Models\Settings;

class MailController extends Controller
{
    public function sendMail(Request $oRequest)
    {
        $this->validate($oRequest,[
            'sender_name' => 'required',
            'sender_email' => 'required|email|valid_email',
            'sender_message' => 'required',
            'sender_file' => 'nullable|file'
        ]);
        $subject = 'TEST MESSAGE';

        $aEmails = explode(',', Settings::getValue('feedback_email'));
        $aEmails = array_map('trim',$aEmails);
        if(empty($aEmails))
            return back()->with('error','Message could not be sent');

        $oMail = new Mail();
        $oMail->from = ['admin@vergil.com','vergil studio'];
        $oMail->to = $aEmails;
        $oMail->subject = $subject;
        if($oRequest->has('sender_file'))
        {
            $oFile = $oRequest->file('sender_file');

            $oMail->attachment = [
                [$oFile->path(),'attached_file.'.$oFile->extension()]
            ];
        }
        try {
            $oMail->send('email.feedback',[
                'message_author' => $oRequest->input('sender_name'),
                'message_author_email' => $oRequest->input('sender_email'),
                'message_text' =>  $oRequest->input('sender_message'),
            ]);

            return redirect()->route('home');
        } catch (Exception $e) {
            return back()->with('error','Message could not be sent');
        }
    }

    public function mailForm()
    {
        return view('email.mail_form');
    }
}
