<?php

namespace App\Http\Controllers;

use App\Models\Settings;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    //
    public function __construct(){
        $this->middleware('adminPower');
    }
    public function index(){
        return view('pages.settings');
    }

    public function options(){
        $data = Settings::orderBy('set_name','asc')->get();
        return view('pages.options',["data"=>$data]);
    }

    public function getOptionJson(Request $request){
        $optId = $request->get("optId");

        $data = Settings::find($optId);

        $result = "false";
        if (!is_null($data)){
            $result = "true";
        }
        return response()->json(["data"=>$data,"result"=>$result]);
    }

    public function optionUpdate(Request $request){
        $optId = $request->get("opt_id");
        $optValue = $request->get("opt_value");

        $oldOption = Settings::find($optId);
        $oldValue = $oldOption->set_value;
        $optCode = $oldOption->set_code;

        if ($oldValue != $optValue){
            $optLogCtrl = new LogOptionsController();
            $logDesc = $oldValue." -> ".$optValue;
            $optLogCtrl->addLog($optCode,$oldValue,$optValue,$logDesc);

            $oldOption->set_value = $optValue;
            $oldOption->save();
        }

        return redirect()->route("options");




    }
}
