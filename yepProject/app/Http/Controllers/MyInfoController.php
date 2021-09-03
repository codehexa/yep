<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class MyInfoController extends Controller
{
    //
    public function __construct(){
        return $this->middleware("auth");
    }

    public function info(){

        $user = Auth::user();
        return view("my.info",['user'=>$user]);
    }

    public function modify(Request $request){
        $upName = $request->get("up_name");
        $upZoomId = $request->get("up_zoom_id");

        $user = Auth::user();

        try {
            $user->name = $upName;
            $user->zoom_id = $upZoomId;

            $user->save();
            return redirect("/home");
        }catch (\Exception $exception){
            return redirect()->back()->withErrors(['msg'=>'FAIL_TO_MODIFY']);
        }
    }

    public function pwChange(){
        return view ('my.pw_change');
    }

    public function pwChangeDo(Request $request){
        $nowPw = $request->get("up_passwd1");
        $pw2  = $request->get("up_passwd2");
        $pw3 = $request->get("up_passwd3");

        if ($pw2 != $pw3){
            return redirect()->back()->withErrors(['msg'=>'FAIL_TO_MODIFY']);
        }

        $user = Auth::user();
        if (Hash::check($nowPw,$user->getAuthPassword())){
            $user->password = Hash::make($pw2);
            $user->save();
            return redirect("/home");
        }else{
            return redirect()->back()->withErrors(['msg'=>'FAIL_TO_MODIFY']);
        }
    }
}
