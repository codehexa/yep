<?php

namespace App\Http\Controllers;

use App\Models\Academies;
use Illuminate\Http\Request;

class AcademyController extends Controller
{
    //
    private $logMode;
    private $logCategory = "ACADEMY";
    private $logTarget;
    private $logDesc;

    public function index(){
        $data = Academies::orderBy('ac_name','asc')->get();

        return view('pages.academies',["data"=>$data]);
    }

    // add academy
    public function add(Request $request){
        $upName = $request->get("up_name");

        $academy = Academies::where("ac_name","=",$upName)->first();

        if (is_null($academy)){
            $academy = new Academies();
            $academy->ac_name = $upName;
        }else{
            return redirect()->back()->withErrors(['msg'=>'ALREADY_HAS']);
        }

        try {
            $academy->save();

            $this->logMode = "add";
            $this->logTarget = $academy->id;
            $this->logDesc = trans("logstrings.str_add_academy");

            $logCtrl = new LogAcademiesController();
            $logCtrl->store($this->logCategory,$this->logMode,$this->logTarget,$this->logDesc);
            return redirect()->route('academyManage');
        }catch (\Exception $e){
            return redirect()->back()->withErrors(['msg'=>'FAIL_TO_SAVE']);
        }
    }

    public function infoJson(Request $request){
        $id = $request->get("up_id");

        $data = Academies::find($id);

        if (is_null($data)){
            $result = "false";
        }else{
            $result = "true";
        }

        return response()->json(["result"=>$result,"data"=>$data]);
    }

    public function store(Request $request){
        $id = $request->get("up_id");
        $name = $request->get("up_name");

        $academy = Academies::find($id);

        $old_name = $academy->ac_name;

        $academy->ac_name = $name;

        try {
            $academy->save();
            $this->logMode = "modify";
            $this->logTarget = $id;
            $this->logDesc = trans("logstrings.academy_modify_done",["OLD_VALUE"=>$old_name,"NEW_VALUE"=>$name]);

            $logCtrl = new LogAcademiesController();
            $logCtrl->store($this->logCategory, $this->logMode,$this->logTarget,$this->logDesc);

            return redirect()->route("academyManage");

        }catch (\Exception $e){
            return redirect()->route("academyManage")->withErrors(["msg"=>"FAIL_TO_MODIFY"]);
        }
    }

    public function delete(Request $request){
        $delId = $request->get("del_id");

        $data = Academies::find($delId);

        try {
            $data->delete();

            $this->logMode = "delete";
            $this->logTarget = $delId;
            $this->logDesc = trans("logstrings.academy_delete",["TARGET_ID"=>$delId]);

            $logCtrl = new LogAcademiesController();
            $logCtrl->store($this->logCategory, $this->logMode, $this->logTarget, $this->logDesc);

            return redirect()->route("academyManage");

        }catch (\Exception $e){
            return redirect()->back()->withErrors(["msg"=>"FAIL_TO_DELETE"]);
        }
    }
}
