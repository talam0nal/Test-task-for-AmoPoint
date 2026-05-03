<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use PHPMailer\PHPMailer\PHPMailer;

class Mail extends Model
{
    public $from;
    public $to;
    public $subject;
    public $attachment;
    private $phpmailer;

    public function __construct()
    {
        parent::__construct();
        $this->phpmailer = new PHPMailer(true);

        $this->phpmailer->isMail();         // Set mailer to use mail() function
        $this->phpmailer->CharSet = 'UTF-8';
        $this->phpmailer->isHTML(true);

    }

    public function send($view, array $arguments = [])
    {
        if(!empty($this->phpmailer))
        {

            //Recipients
            $this->setFrom();
            $this->setTo();

            //$oMail->addReplyTo('your-email@gmail.com', 'Mailer');
            //$oMail->addCC('his-her-email@gmail.com');
            //$oMail->addBCC('his-her-email@gmail.com');

            //Attachments (optional)
            $this->setAttachment();

            //Content
																	// Set email format to HTML
            $this->phpmailer->Subject = $this->subject;
            $this->phpmailer->Body   = view($view,$arguments)->render();						// message
            $this->phpmailer->send();
        }
    }

    private function setFrom()
    {
        if(is_array($this->from))
        {
            $from_array = array_values($this->from);
            if(count($from_array)>=2)
                $this->phpmailer->setFrom($from_array[0],$from_array[1]);
            else
                $this->phpmailer->setFrom($from_array[0]);
        }
        else
            $this->phpmailer->setFrom($this->from);
    }

    private function setTo()
    {
        if(!is_array($this->to))
        {
            $aEmails = [$this->to];
        }
        else
            $aEmails = $this->to;
        foreach($aEmails as $sMail)
            $this->phpmailer->addAddress($sMail);	                        // Add a recipient, Name is optional
    }

    private function setAttachment()
    {
        if(!empty($this->attachment))
        {
            foreach($this->attachment as $aAttachment)
            {
                if(is_array($aAttachment))
                {
                    $attachment_data = array_values($aAttachment);
                    if(count($attachment_data)>=2)
                        $this->phpmailer->addAttachment($attachment_data[0], $attachment_data[1]);
                    else
                        $this->phpmailer->addAttachment($attachment_data[0]);
                }
                else
                    $this->phpmailer->addAttachment($aAttachment);
            }
        }
    }
}
