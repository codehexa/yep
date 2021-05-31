<?php

namespace App\Http\Controllers;

use App\Models\Academies;
use App\Models\Classes;
use App\Models\Configurations;
use App\Models\schoolGrades;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ClassesController extends Controller
{
    //
    public function __construct(){
        $this->middleware('auth');
    }
    public function index($acaid=""){
        $user = Auth::user();

        $grades = schoolGrades::orderBy("scg_index","asc")->get();
        $gradesArray = [];
        foreach($grades as $grade){
            $g_id = $grade->id;
            $g_pre_code = $grade->scg_pre_code;
            switch ($g_pre_code){
                case Configurations::$SCHOOL_PRE_GRADE_KINDER:
                    $g_kr = Configurations::$SCHOOL_PRE_GRADE_KINDER_HAN;
                    break;
                case Configurations::$SCHOOL_PRE_GRADE_ELEMENT:
                    $g_kr = Configurations::$SCHOOL_PRE_GRADE_ELEMENT_HAN;
                    break;
                case Configurations::$SCHOOL_PRE_GRADE_MIDDLE:
                    $g_kr = Configurations::$SCHOOL_PRE_GRADE_MIDDLE_HAN;
                    break;
                case Configurations::$SCHOOL_PRE_GRADE_HIGH:
                    $g_kr = Configurations::$SCHOOL_PRE_GRADE_HIGH_HAN;
                    break;
                case Configurations::$SCHOOL_PRE_GRADE_UNIVERSITY:
                    $g_kr = Configurations::$SCHOOL_PRE_GRADE_UNIVERSITY_HAN;
                    break;
            }

            $gradesArray[] = ["id"=>$g_id,"scg_name"=>$g_kr." ".$grade->scg_name];
        }

        if ($user->power != Configurations::$USER_POWER_ADMIN){
            $acId = $user->academy_id;
            $academies = Academies::where("id","=",$acId)->get();
            $data = Classes::where("ac_id","=",$acId)
                ->orderBy('ac_id','asc')
                ->orderBy('class_name','asc')
                ->get();
        }else{
            $academies = Academies::orderBy("ac_name")->get();

            if ($acaid != ""){
                $data = Classes::where('ac_id','=',$acaid)
                    ->orderBy('ac_id','asc')
                    ->orderBy('class_name','asc')
                    ->get();
            }else{
                $data = Classes::orderBy('ac_id','asc')
                    ->orderBy('class_name','asc')
                    ->get();
            }

        }

        $gradesObj = json_decode(json_encode($gradesArray),FALSE);

        return view("classes.list",["data"=>$data,"academies"=>$academies,"grades"=>$gradesObj,"acaid"=>$acaid]);
    }

    public function add(Request $request){
        $upAcId = $request->get("up_ac_id");
        $upClassId = $request->get("up_scg_id");
        $upName = $request->get("up_name");
        $upShow = $request->get("up_show");
        $upDesc = $request->get("up_desc");

        $checkClass = Classes::where('ac_id','=',$upAcId)->where('class_name','=',$upName)->first();
        if (is_null($checkClass)){
            $newClass = new Classes();
            $newClass->scg_name = $upName;
            $newClass->ac_id = $upAcId;
            $newClass->sg_id = $upClassId;
            $newClass->show = $upShow;
            $newClass->class_desc = $upDesc;

            try {
                $newClass->save();
                return redirect()->route("classes");
            }catch (\Exception $e){
                return redirect()->back()->withErrors(['msg'=>"FAIL_ALREADY_HAS"]);
            }

        }
    }

    public function infoJson(Request $request){
        $cid = $request->get("cid");

        $data = Classes::find($cid);

        $result = "false";
        if (!is_null($data)){
            $result = "true";
        }

        return response()->json(["result"=>$result,"data"=>$data]);
    }

    public function store(Request $request){
        $user = Auth::user();
        $upId = $request->get("up_id");
        $acId = $request->get("up_ac_id");
        $scgId = $request->get("up_scg_id");
        $upName = $request->get("up_name");
        $upShow = $request->get("up_show");
        $upDesc = $request->get("up_desc");

        $old = Classes::find($upId);

        $old->class_desc = $upDesc;



        if ($old->class_name != $upName){
            $logClassCtrl = new LogClassesController();
            $log_category = "class_name";
            $log_mode = "modify";
            $target_id = $upId;
            $log_desc = $old->class_name ." -> ".$upName;
            $logClassCtrl->addLog($log_category,$log_mode,$target_id,$log_desc);

            $old->class_name = $upName;
        }

        if ($old->ac_id != $acId){
            $logClassCtrl = new LogClassesController();
            $log_category = "ac_id";
            $log_mode = "modify";
            $target_id = $upId;
            $log_desc = $old->ac_id ." -> ".$acId;
            $logClassCtrl->addLog($log_category,$log_mode,$target_id,$log_desc);

            $old->ac_id = $acId;
        }

        if ($old->sg_id != $scgId){
            $logClassCtrl = new LogClassesController();

            $log_category = "sg_id";
            $log_mode = "modify";
            $target_id = $upId;
            $log_desc = $old->sg_id ." -> ".$scgId;
            $logClassCtrl->addLog($log_category,$log_mode,$target_id,$log_desc);

            $old->sg_id = $scgId;
        }

        if ($old->show != $upShow){
            $logClassCtrl = new LogClassesController();
            $log_category = "show";
            $log_mode = "modify";
            $target_id = $upId;
            $log_desc = $old->show ." -> ".$upShow;
            $logClassCtrl->addLog($log_category,$log_mode,$target_id,$log_desc);

            $old->show = $upShow;
        }

        $old->save();

        return redirect()->route("classes");
    }

    public function getTeacher(Request $request){
        $clsId = $request->get('cls_id');
        $classObj = Classes::find($clsId);

        $academy_id = $classObj->ac_id;

        $data = User::where('academy_id','=',$academy_id)->where('power','=',Configurations::$USER_POWER_TEACHER)
            ->orderBy('name','asc')->get();

        return response()->json(['data'=>$data]);
    }

    public function setClassTeacher(Request $request){
        $classId = $request->get("up_class_id");
        $userId = $request->get("up_user_id");

        $cls = Classes::find($classId);

        $old_value = $cls->teacher_id;
        $cls->teacher_id = $userId;

        if ($old_value != $userId){
            try {
                $cls->save();
                $clsLogCtrl = new LogClassesController();
                $category = "teacher_id";
                $mode = "modify";
                $target = $classId;
                $desc = $old_value." -> ".$userId;

                $clsLogCtrl->addLog($category,$mode,$target,$desc);

                return redirect()->route("classes");
            }catch (\Exception $e){
                return redirect()->back()->withErrors(['msg'=>'FAIL_TO_SAVE']);
            }
        }else{
            return redirect()->route("classes");
        }
    }


}
