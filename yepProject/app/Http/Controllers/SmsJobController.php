<?php

namespace App\Http\Controllers;

use App\Models\Academies;
use App\Models\Classes;
use App\Models\Configurations;
use App\Models\Curriculums;
use App\Models\schoolGrades;
use App\Models\SmsScores;
use App\Models\Students;
use App\Models\Subjects;
use App\Models\TestForms;
use App\Models\TestFormsItems;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SmsJobController extends Controller
{
    //
    public function __construct(){
        $this->middleware("auth");
    }

    public function index($acId='',$gradeId='',$classId='',$tfId='',$year='',$weeks=''){

        $user = Auth::user();
        $nowPower = $user->power;

        $data = [];
        $tItems = [];
        $testForms = [];

        if ($nowPower != Configurations::$USER_POWER_ADMIN){
            $academies = Academies::where('id','=',$user->academy_id)->orderBy('ac_name','asc')->get();
        }else{
            $academies = Academies::orderBy('ac_name','asc')->get();
        }

        $schoolGrades = schoolGrades::orderBy('scg_index','asc')->get();

        if ($acId != "" && $gradeId != ""){
            $classes = Classes::where('ac_id','=',$acId)
                ->where('sg_id','=',$gradeId)
                ->where('show','=','Y')
                ->orderBy('class_name','asc')->get();
        }elseif ($acId != "" && $gradeId == "") {
            $classes = Classes::where('ac_id','=',$acId)
                ->where('sg_id','=',$gradeId)
                ->where('show','=','Y')
                ->orderBy('class_name','asc')->get();
        }else{
            $classes = Classes::where('show','=','Y')->orderBy('class_name','asc')->get();
        }

        // 여기까지 기초 필드 항목 값들.
        $hasDouble = "N";

        if ($acId != ""  && $gradeId != "" && $classId != "" && $tfId != "" && $year != "" && $weeks != ""){
            $formCtrl = new TestFormsController();
            $testForms = TestForms::where('grade_id','=',$gradeId)->orderBy('form_title','asc')->get();
            $testForm = TestForms::find($tfId);
            $testFormItems = TestFormsItems::where('tf_id','=',$tfId)->where('sj_parent_id','=',0)->orderBy('sj_index','asc')->get();
            $tItems = [];
            foreach($testFormItems as $formItem){
                if ($formItem->sj_has_child == "Y"){
                    $hasDouble = "Y";
                    $children = $formCtrl->getTestFormItemChildren($formItem->id);
                    $formItem->setAttribute("child_size",sizeof($children));
                    $tItems[] = $formItem;
                    foreach($children as $child){
                        $tItems[] = $child;
                    }
                }else{
                    $tItems[] = $formItem;
                }
            }

            $students = Students::where("class_id","=",$classId)->get();

            foreach ($students as $student){
                $stId = $student->id;
                $checkScore = SmsScores::where('sg_id','=',$gradeId)
                    ->where('year','=',$year)
                    ->where('week','=',$weeks)
                    ->where('tf_id','=',$tfId)
                    ->where('st_id','=',$stId)
                    ->where('cl_id','=',$classId)
                    ->first();

                if (is_null($checkScore)){
                    $newSmsScore = new SmsScores();
                    $newSmsScore->sg_id = $gradeId;
                    $newSmsScore->writer_id = $user->id;
                    $newSmsScore->year = $year;
                    $newSmsScore->week = $weeks;
                    $newSmsScore->tf_id = $tfId;
                    $newSmsScore->st_id = $stId;
                    $newSmsScore->cl_id = $classId;
                    $newSmsScore->opinion = "";
                    $newSmsScore->sent = "N";
                    $newSmsScore->score_count = sizeof($tItems);
                    for($i=0; $i < sizeof($tItems); $i++){
                        $fieldName = Configurations::$TEST_SCORES_FIELD_PREFIX.$i;
                        $newSmsScore->$fieldName = "0";
                    }

                    $newSmsScore->save();
                    $newSmsScore->setAttribute("studentItem",$student);

                    $data[] = $newSmsScore->toArray();
                }else{
                    $checkScore->setAttribute("studentItem",$student);
                    $data[] = $checkScore->toArray();
                }
            }   // end for

            //dd($data);
        }else{
            $testForm = [];
        }

        //dd($data);

        return view("sms.index",["testForms"=>$testForms,"testForm"=>$testForm,"tItems"=>$tItems,"data"=>$data,"hasDouble"=>$hasDouble,
            "academies"=>$academies, "schoolGrades"=>$schoolGrades, "classes"=>$classes,
            "rAcId"=>$acId,"rGradeId"=>$gradeId,"rClId"=>$classId,"rTfId"=>$tfId,
            "rY"=>$year,"rW"=>$weeks,"indexN"=>"0"
        ]);
    }

    public function insert_array($arr,$idx,$add){
        $arr_front = array_slice($arr,0,$idx);
        $arr_end = array_slice($arr,$idx);
        $arr_front[] = $add;
        return array_merge($arr_front,$arr_end);
    }

    public function getFormsJson(Request $request){
        $acId = $request->get("section_academy");
        $gradeId = $request->get("section_grade");
        $classId = $request->get("section_class");

        $data = TestForms::whereIn('ac_id',[$acId,'0'])
            ->where('grade_id','=',$gradeId)
            ->orderBy('form_title','asc')
            ->get();

        return response()->json(['data'=>$data]);
    }

    public function getClassesJson(Request $request){
        $acId = $request->get("section_academy");
        $gradeId = $request->get("section_grade");

        if ($gradeId != ""){
            $data = Classes::where('ac_id','=',$acId)
                ->where('sg_id','=',$gradeId)
                ->orderBy('class_name','asc')->get();
        }else{
            $data = Classes::where('ac_id','=',$acId)->orderBy('class_name','asc')->get();
        }

        return response()->json(['data'=>$data]);
    }

    public function getTestFormsJson(Request $request){
        $acId = $request->get("section_academy");
        $gradeId = $request->get("section_grade");

        if ($gradeId != ""){
            $data = TestForms::whereIn('ac_id',[$acId,'0'])
                ->where('grade_id','=',$gradeId)
                ->orderBy('form_title','asc')
                ->get();
        }else{
            $data = TestForms::whereIn('ac_id',[$acId,'0'])
                ->orderBy('form_title','asc')
                ->get();
        }

        return response()->json(['data'=>$data]);
    }

    public function saveOpinion(Request $request){
        $nowId = $request->get("info_id");
        $opinion = $request->get("info_name");

        $result = "false";

        $Score = SmsScores::find($nowId);

        $Score->opinion = $opinion;

        try {
            $Score->save();
            $result = "true";
            return response()->json(["result"=>$result]);
        }catch (\Exception $exception){
            return response()->json(["result"=>$result]);
        }
    }

    public function saveSmsEach(Request $request){
        $scId = $request->get("scId");
        $scores = $request->get("scores");
        $opinion = $request->get("opinion");

        $score = SmsScores::find($scId);
        $score->opinion = $opinion;
        $arr = explode(",",$scores);
        for ($i=0; $i < sizeof($arr); $i++){
            $fieldName = Configurations::$TEST_SCORES_FIELD_PREFIX.$i;
            $score->$fieldName = $arr[$i];
        }

        try {
            $score->save();
            $result = "true";
        }catch (\Exception $exception){
            $result = "false";
        }

        return response()->json(["result"=>$result]);
    }

    public function sendSms(Request $request){
        dd($request);
    }
}
