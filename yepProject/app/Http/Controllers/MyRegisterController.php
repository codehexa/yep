<?php

namespace App\Http\Controllers;

use App\Models\Configurations;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class MyRegisterController extends Controller
{
    //
    public function register(){
        return view('myRegister');
    }

    public function regDo(Request $request){
        $usName = $request->get("usname");
        $email = $request->get("email");
        $usPasswd = $request->get("password");
        $usPasswd2 = $request->get("password_2");
        $zoomId = $request->get("up_zoom_id");
        $tel = $request->get("up_tel");

        if ($usPasswd != $usPasswd2){
            return redirect()->back()->withErrors(["msg"=>"PASSWORD_NOT_MATCH"]);
        }

        $check = User::where('email','=',$email)->count();

        if ($check > 0){
            return redirect()->back()->withErrors(['msg'=>'HAS_BEEN_REGISTER']);
        }else{
            $user = new User();
            $user->uid = $email;
            $user->name = $usName;
            $user->email = $email;
            $user->password = Hash::make($usPasswd);
            $user->tel = $tel;
            $user->power = Configurations::$USER_POWER_TEACHER;
            $user->live = "N";
            $user->zoom_id = $zoomId;

            try {
                $user->save();
                return redirect("/regDone");
            }catch (\Exception $exception){
                return redirect()->back()->withErrors(["msg"=>"FAIL_TO_SAVE"]);
            }
        }
    }

    public function regDone(){
        return view('/myRegDone');
    }

    public function passwdReset(){
        return view('/passwdReset');
    }

    public function passwdResetDo(Request $request){
        $email = $request->get("email");
        $name = $request->get("usname");

        $check = User::where('email','=',$email)->where('name','=',$name)->first();
        if (!is_null($check)){
            $newPasswd = $this->makeNewPw();
            try {
                $check->update(['password'=>Hash::make($newPasswd)]);

                //$this->sendEmail($email,$newPasswd);
                $aligoCtrl = new AligoController();
                $tel = $check->tel;
                $tel = preg_replace("/-/","",$tel);
                $msg = trans('strings.str_password_email_text',["PASSWD"=>$newPasswd]);
                $msg_type = "SMS";
                $title = trans('strings.lb_password_reset');
                $destination = $tel.":".$name;
                $send = $aligoCtrl->singleSend($tel,$msg,$msg_type,$title,$destination);

/*
                $logUserCtrl = new LogUsersController();
                $logUserCtrl->addLog($check->id,$check->id,'password','LOSE','비밀번호변경');*/

                return view("passwdResetDone",["send"=>$send]);

            }catch (\Exception $exception){
                return redirect()->back()->withErrors(['msg'=>'FAIL_TO_UPDATE']);
            }
        }
        return redirect()->back()->withErrors(['msg'=>'NO_MATCH_DATA']);
    }

    public function sendEmail($email,$pw){
        $to = $email;
        $subject = trans('strings.str_password_email_title');
        $message = trans('strings.str_password_email_text',["PASSWD"=>$pw]);
        $from = Configurations::$EMAIL_SENDER;
        $headers = "MIME-Version: 1.0"."\r\n";
        $headers .= "Content-type: text/html; charset=iso-8859-1"."\r\n";
        $headers .= "From: {$from}\r\n".
            "Reply-To: {$from}\r\n".
            "X-Mailer: PHP/".phpversion();

        $messages = "<html><body>".$message."</body></html>";

        if (mail($to,$subject,$messages,$headers)){

        }

    }

    public function makeNewPw(){
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        $length = 8;
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }
}
