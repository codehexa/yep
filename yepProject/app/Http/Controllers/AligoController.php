<?php

namespace App\Http\Controllers;

use App\Models\Configurations;
use Illuminate\Http\Request;

class AligoController extends Controller
{
    //
    public function singleSend($receiver,$message,$msg_type,$title,$destination){
        $url = Configurations::$ALIGO_HOST;
        $port = Configurations::$ALIGO_PORT;
        $key = env("ALIGO_KEY");
        $userId = env("ALIGO_ID");
        $sender = Configurations::$YEP_SENDER_TEL;
        $msg = $message;

        $msg = iconv("UTF-8","EUC-KR",$msg);

        $sms['user_id'] = $userId;
        $sms['key'] = $key;
/*
        $_POST["msg"] = $msg;
        $_POST["receiver"] = $receiver; // 수신 번호
        $_POST['destination']   = $destination; // 수신인 %고객명% 치환
        $_POST["sender"] = $sender;
        $_POST["subject"]   = $title;
        $_POST["msg_type"]  = $msgType; // SMS, LMS, MMS*/

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

        return $res;
    }
}
