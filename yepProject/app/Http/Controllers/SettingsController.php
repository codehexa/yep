<?php

namespace App\Http\Controllers;

use App\Models\Settings;
use App\Models\SmsPageSettings;
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

    public function SmsPageSet(){
        $data = SmsPageSettings::orderBy('id','asc')->first();

        return view("pages.smsPage",["data"=>$data]);
    }

    public function SmsPageSetSave(Request $request){
        $greetings = $request->get("up_greetings");
        //$resultUrl = $request->get("up_result_url");
        $blogGuide = $request->get("up_blog_guide");
        $blogUrl = $request->get("up_blog_url");
        $teacherSay = $request->get("up_teacher_say");
        $opt1 = $request->get("up_sps_opt_1");
        $opt2 = $request->get("up_sps_opt_2");

        $useTop = "N"; $useBottom = "N";
        $useTopReq = $request->get("up_use_top");
        $useBottomReq = $request->get("up_use_bottom");

        if (isset($useTopReq)) $useTop = $useTopReq;
        if (isset($useBottomReq)) $useBottom = $useBottomReq;

        $data = SmsPageSettings::orderBy('id','asc')->first();
        if (is_null($data)){
            $data = new SmsPageSettings();
        }
        $data->greetings = $greetings;
        $data->blog_guide = $blogGuide;
        $data->blog_link_url = $blogUrl;
        $data->teacher_title = $teacherSay;
        $data->sps_opt_1 = $opt1;
        $data->sps_opt_2 = $opt2;
        $data->use_top = $useTop;
        $data->use_bottom = $useBottom;

        try {
            $data->save();

            return redirect("/smsPageSet");
        }catch (\Exception $exception){
            dd($exception);
            return redirect()->back()->withErrors(['msg'=>'FAIL_TO_UPDATE']);
        }
    }
}
