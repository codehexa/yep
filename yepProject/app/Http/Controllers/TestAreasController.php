<?php

namespace App\Http\Controllers;

use App\Models\Configurations;
use App\Models\schoolGrades;
use App\Models\Settings;
use App\Models\TestAreas;
use Illuminate\Http\Request;

class TestAreasController extends Controller
{
    //
    public function list($grade = ''){
        if ($grade != ''){
            $data = TestAreas::where('ta_school_grade','=',$grade)->orderBy('ta_depth','asc')->orderBy('parent_id','asc')->orderBy('ta_name','asc')->get();
        }else{
            $data = TestAreas::orderBy('ta_depth','asc')->orderBy('parent_id','asc')->orderBy('ta_name','asc')->get();
        }


        $schoolGrades = schoolGrades::orderBy('scg_index','asc')->get();

        $maxScoreObj = Settings::where('set_code','=',Configurations::$SETTINGS_TEST_MAX_SCORE)->first();
        $maxScore = $maxScoreObj->set_value;

        $parents = TestAreas::where('ta_depth','=',0)->orderBy('ta_name','asc')->get();

        return view("testareas.list",["data"=>$data,"grades"=>$schoolGrades,"parents"=>$parents,"maxScore"=>$maxScore,"rGrade"=>$grade]);
    }

    public function add(Request $request){
        $gradeId = $request->get("info_school_grade_id");
        $name = $request->get("info_name");
        $parent = $request->get("info_parent_id");
        $code = $request->get("info_code");
        $score = $request->get("info_max_score");

        $depth = 0;

        if ($parent == "") {
            $parent = "0";
            $depth = 0;
        }else{
            $depth = 1;
        }

        $check = TestAreas::where("ta_name",'=',$name)->where("ta_school_grade",'=',$gradeId)
            ->orWhere('ta_code','=',$code)->count();

        if ($check > 0){
            return redirect()->back()->withErrors(['msg'=>'FAIL_ALREADY_HAS']);
        }else{
            $ta = new TestAreas();
            $ta->ta_school_grade = $gradeId;
            $ta->ta_name = $name;
            $ta->parent_id = $parent;
            $ta->ta_code = $code;
            $ta->ta_child_count = 0;
            $ta->ta_max_score = $score;
            $ta->ta_depth = $depth;

            try {
                $ta->save();

                if ($ta->parent_id == "0"){
                    $ta->parent_id = $ta->id;
                }

                $ta->save();

                $this->updateChild($ta->id);

                $logMode = "add";
                $logTarget = $ta->id;
                $logOld = "";
                $logNew = "";

                $this->addLog($logMode,$logTarget,$logOld,$logNew);

                return redirect()->route("testAreas");
            }catch (\Exception $e){
                return redirect()->back()->withErrors(["msg"=>"FAIL_TO_SAVE"]);
            }
        }
    }

    public function info(Request $request){
        $cid = $request->get("cid");

        $data = TestAreas::find($cid);

        $parents = TestAreas::where('ta_depth','=',0)->orderBy('ta_name','asc')->get();

        return response()->json(['data'=>$data,'result'=>'true','parents'=>$parents]);
    }

    public function del(Request $request){
        $delId = $request->get("del_id");

        $obj = TestAreas::find($delId);

        try {
            $obj->delete();
            $mode = "delete";
            $target = $delId;
            $oldVal = $obj->ta_name;
            $newVal = "";

            $this->addLog($mode,$target,$oldVal,$newVal);

            return redirect()->route("testAreas");
        }catch (\Exception $e){
            return redirect()->back()->withErrors(["msg"=>"FAIL_TO_DELETE"]);
        }
    }

    public function store(Request $request){
        $upId = $request->get("info_id");
        $gradeId = $request->get("info_school_grade_id");
        $upName = $request->get("info_name");
        $parent_id = $request->get("info_parent_id");
        $code = $request->get("info_code");
        $score = $request->get("info_max_score");

        $old = TestAreas::find($upId);

        if ($gradeId != $old->ta_school_grade){
            $mode = "modify";
            $oldVal = $old->ta_school_grade;
            $newVal = $gradeId;
            $this->addLog($mode,$upId,$oldVal,$newVal);

            $old->ta_school_grade = $gradeId;
        }

        if ($parent_id != $old->parent_id){
            $mode = "modify";
            $oldVal = $old->parent_id;
            $newVal = $parent_id;
            $this->addLog($mode,$upId,$oldVal,$newVal);

            $old->parent_id = $parent_id;
        }

        if ($upName != $old->ta_name){
            $mode = "modify";
            $oldVal = $old->ta_name;
            $newVal = $upName;
            $this->addLog($mode,$upId,$oldVal,$newVal);

            $old->ta_name = $upName;
        }

        if ($code != $old->ta_code){
            $mode = "modify";
            $oldVal = $old->ta_code;
            $newVal = $code;
            $this->addLog($mode,$upId,$oldVal,$newVal);

            $old->ta_code = $code;
        }

        if ($score != $old->ta_max_score){
            $mode = "modify";
            $oldVal = $old->ta_max_score;
            $newVal = $score;
            $this->addLog($mode,$upId,$oldVal,$newVal);

            $old->ta_max_score = $score;
        }

        try {
            $old->save();

            $this->updateChild($upId);

            return redirect()->route("testAreas");
        }catch (\Exception $e){
            return redirect()->back()->withErrors(["msg"=>"FAIL_TO_MODIFY"]);
        }
    }

    private function addLog($mode,$target,$old,$new){
        $ctrl = new LogTestAreasController();
        $ctrl->addLog($mode,$target,$old,$new);
    }

    private function updateChild($id){
        $now = TestAreas::find($id);

        $count = TestAreas::where('parent_id','=',$now->parent_id)->count();
        if (is_null($count)) $count = 0;

        $parent = TestAreas::find($now->parent_id);
        $parent->ta_child_count = $count;
        $parent->save();
    }
}
