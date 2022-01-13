<?php

namespace App\Http\Controllers;

use App\Models\Configurations;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class TempController extends Controller
{
    //
    public function tmp(){
        $user = User::find(1);
        $user->password = Hash::make("thsdhstn");

        $user->save();

    }

    public function mail(){
        $to = 'codehexa1@gmail.com';
        $subject = 'Studying sending email in Laravel';
        $data = [
            'title' => 'Hi there, Show me the money, Plz....',
            'body'  => 'This is the body of an email message',
            'user'  => User::find(1)
        ];

        return Mail::send('welcome', $data, function($message) use($to, $subject) {
            $message->to($to)->subject($subject);
        });
    }

    public function sms(){

        $message = "aligo test";
        $receiver = "01023768670";
        $destination = "01023768670|tester";
        $title = "test";
        $msg_type = "SMS";
        $url = Configurations::$ALIGO_HOST;
        $port = Configurations::$ALIGO_PORT;
        $key = env("ALIGO_KEY");
        $userId = env("ALIGO_ID");
        $sender = Configurations::$YEP_SENDER_TEL;
        $msg = $message;


        $msg = iconv("UTF-8","EUC-KR",$msg);

        $sms['user_id'] = $userId;
        $sms['key'] = $key;

        $sms['msg'] = $msg;
        $sms['receiver']    = $receiver;
        $sms['destination'] = $destination;
        $sms['sender']  = $sender;
        $sms['title']   = $title;
        $sms['msg_type']    = $msg_type;

        $cinit = curl_init();
        curl_setopt($cinit, CURLOPT_PORT, $port);
        curl_setopt($cinit, CURLOPT_URL, $url);
        curl_setopt($cinit, CURLOPT_POST, 1);
        curl_setopt($cinit, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($cinit, CURLOPT_POSTFIELDS, $sms);
        curl_setopt($cinit, CURLOPT_SSL_VERIFYPEER, FALSE);
        $res = curl_exec($cinit);
        curl_close($cinit);

        echo $res;
        echo (iconv("utf-8","EUC-KR",$res));

        return $res;

        /*$newPasswd = "a891dfasdf";
        $user = User::find(4);
        $smsCtrl = new AligoController();
        $tel = $user->tel;
        $name = $user->name;
        $tel = preg_replace("/-/","",$tel);
        $msg = trans('strings.str_password_email_text',["PASSWD"=>$newPasswd]);
        $msg_type = "LMS";
        $title = trans('strings.lb_password_reset');
        $destination = $tel.":".$name;
        $send = $smsCtrl->singleSend($tel,$msg,$msg_type,$title,$destination);

        dd($send);*/

        $aligoCtrl = new AligoController();
        $aligoCtrl->sendScoreResults();
    }
}
