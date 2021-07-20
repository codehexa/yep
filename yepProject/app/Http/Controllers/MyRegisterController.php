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
            $user->power = Configurations::$USER_POWER_TEACHER;
            $user->live = "N";

            try {
                $user->save();
                return redirect("/regDone");
            }catch (\Exception $exception){
                dd($exception);
                return redirect()->back()->withErrors(["msg"=>"FAIL_TO_SAVE"]);
            }
        }
    }

    public function regDone(){
        return view('/myRegDone');
    }
}
