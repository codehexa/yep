<?php

namespace App\Http\Controllers;

use App\Models\BmsSendLogs;
use App\Models\Configurations;
use App\Models\SmsPageSettings;
use App\Models\SmsSendResults;
use App\Models\TestAreas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AligoController extends Controller
{
    //
    public function singleSend($receiver,$message,$title,$destination){
        $url = Configurations::$ALIGO_HOST;
        $port = Configurations::$ALIGO_PORT;
        $key = env("ALIGO_KEY");
        $userId = env("ALIGO_ID");
        $sender = Configurations::$YEP_SENDER_TEL;
        $msg = $message;

        $sms['user_id'] = $userId;
        $sms['key'] = $key;

        $sms['msg'] = $msg;
        $sms['receiver']    = $receiver;
        $sms['destination'] = $destination;
        $sms['sender']  = $sender;
        $sms['title']   = $title;
        $sms['msg_type']    = Configurations::$ALIGO_MSG_TYPE;

        $cinit = curl_init();
        curl_setopt($cinit, CURLOPT_PORT, $port);
        curl_setopt($cinit, CURLOPT_URL, $url);
        curl_setopt($cinit, CURLOPT_POST, 1);
        curl_setopt($cinit, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($cinit, CURLOPT_POSTFIELDS, $sms);
        curl_setopt($cinit, CURLOPT_SSL_VERIFYPEER, FALSE);
        $res = curl_exec($cinit);
        curl_close($cinit);

        $jsonDecode = json_decode($res);

        return $jsonDecode->result_code;
    }

    public function sendScoreResults(){
        $smsSend = SmsSendResults::where('ssr_status','=',Configurations::$SMS_SEND_RESULTS_READY)
            ->orderBy('id','asc')->take(Configurations::$BMS_MAX_MASS_SIZE)->get();

        // if Local PC
        //$pageSet = SmsPageSettings::latest()->first();
        $pageSet = SmsPageSettings::first();

        $greeting = $pageSet->greetings;


        foreach ($smsSend as $sms){
            $receiver = $sms->sms_tel_no;
            $message = $sms->sms_msg;
            $title =$greeting;
            $destination = $receiver."|".$sms->Student->student_name;

            $res = $this->singleSend($receiver,$message,$title,$destination);

            if($res == "1"){
                $sms->ssr_status = Configurations::$SMS_SEND_RESULTS_SENT;
                $sms->save();
            }else{
                $sms->ssr_status = Configurations::$SMS_SEND_RESULTS_FALSE;
                $sms->save();
            }
        }
    }

    // 수업 공지 전송
    public function sendClassLms(){
        $url = Configurations::$ALIGO_HOST;
        $port = Configurations::$ALIGO_PORT;
        $key = env("ALIGO_KEY");
        $userId = env("ALIGO_ID");
        $sender = Configurations::$YEP_SENDER_TEL;

        $lmsData = BmsSendLogs::where('bsl_result_msg','=',Configurations::$BMS_SENT_MESSAGE_READY)->get();

        foreach($lmsData as $lmsDatum){
            $sms = [];
            $sms['sender']  = $sender;
            $sms['user_id'] = $userId;
            $sms['key'] = $key;
            $sms['msg_type']    = "SMS";//Configurations::$ALIGO_MSG_TYPE;

            $msgRoot = $lmsDatum->bsl_send_text;
            $msgRoot = "abckdl";

            $tels = $lmsDatum->tels;
            $telsArray = explode(",",$tels);
            for ($i=0; $i < sizeof($telsArray); $i++){
                $recN = $i + 1;
                $recTel = $telsArray[$i];
                $recTel = str_replace("-","",$recTel);
                $sms['rec_'.$recN] = $recTel;
                $sms['msg_'.$recN] = $msgRoot;
            }
            $sms['cnt'] = sizeof($telsArray);

            $cinit = curl_init();
            curl_setopt($cinit, CURLOPT_PORT, $port);
            curl_setopt($cinit, CURLOPT_URL, $url);
            curl_setopt($cinit, CURLOPT_POST, 1);
            curl_setopt($cinit, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($cinit, CURLOPT_POSTFIELDS, $sms);
            curl_setopt($cinit, CURLOPT_SSL_VERIFYPEER, FALSE);
            $res = curl_exec($cinit);
            curl_close($cinit);

            $jsonDecode = json_decode($res);

            if ($jsonDecode->result_code == "1"){
                $lmsDatum->bsl_result_msg = Configurations::$BMS_SENT_MESSAGE_SENT;
                $lmsDatum->bsl_sent_date = now();
            }else{
                $lmsDatum->bsl_result_msg = Configurations::$BMS_SENT_MESSAGE_FALSE;
                $lmsDatum->bsl_fault_msg = $jsonDecode->message.$sms['msg_1'];
                $lmsDatum->bsl_aligo_result_code = $jsonDecode->result_code;
            }
            $lmsDatum->save();
        }
    }
}


