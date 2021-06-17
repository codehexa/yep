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
        $tbl_header_1 = $tbl_header_0 = [];
        $tbl_context = [];

        $data = [];
        $testForms = [];

        $tbl_pre_rows = 0;

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

        if ($acId != ""  && $gradeId != "" && $classId != "" && $tfId != "" && $year != "" && $weeks != ""){
            $testForms = TestForms::where('ac_id','=',$acId)->where('grade_id','=',$gradeId)->orderBy('form_title','asc')->get();
            $testForm = TestForms::find($tfId);
            $fieldsCount = $testForm->subjects_count;
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
                    $newSmsScore->sj_count = $fieldsCount;

                    $newSmsScore->save();

                    $data[] = $newSmsScore;
                }else{
                    $data[] = $checkScore;
                }
            }   // end for

            /* html design */
            $tbl_count = $testForm->subjects_count; // Total 항목을 제외한 갯수.
            $tbl_subjects = [];
            $tbl_saved_cuid = 0;
            $tbl_colspan = 0;

            $tbl_header_0 = []; // 상위 테이블 헤더 1.
            $tbl_header_1 = []; // 하위 테이블 헤더 2.

            for ($i=0; $i < $tbl_count; $i++){
                $tbl_now_subject_field_name = Configurations::$TEST_FORM_IN_SUBJECT_PREFIX.$i;
                $tbl_now_subject_id = $testForm->$tbl_now_subject_field_name;
                $tbl_now_subject = Subjects::find($tbl_now_subject_id);
                $tbl_now_subject_curri_id = $tbl_now_subject->curri_id;

                if ($tbl_now_subject_curri_id > 0){
                    $tbl_pre_rows = 2;  // 이전 필드의 rowspan 값.
                    $nowCurri = Curriculums::find($tbl_now_subject_curri_id);
                    $tbl_curri_name = $nowCurri->curri_name;
                    if ($tbl_saved_cuid != $tbl_now_subject_curri_id){
                        $tbl_colspan = 0;
                        $tbl_colspan++;
                        $tbl_header_0[] = ["cols"=>$tbl_colspan,"title"=>$tbl_curri_name];
                        $tbl_saved_cuid = $tbl_now_subject_curri_id;
                    }else{
                        $tbl_colspan++;
                        $tbl_header_0[sizeof($tbl_header_0) -1]["cols"]=$tbl_colspan;
                        $tbl_header_0[sizeof($tbl_header_0) -1]["title"]=$tbl_curri_name;
                    }
                    $tbl_header_1[] = ["title"=>$tbl_now_subject->sj_title];
                }else{
                    $tbl_saved_cuid = 0;
                    $tbl_colspan = 0;
                    $tbl_header_0[] = ["cols"=>$tbl_colspan,"title"=>$tbl_now_subject->sj_title];
                }
                $tbl_context[] = ["sj_id"=>$tbl_now_subject_id,"cu_id"=>$tbl_now_subject_curri_id];
            }   // end for
        }else{
            $testForm = [];
        }

        $header_total = ["title"=>"ToT"];
        $n = 0;
        for ($i=0; $i < sizeof($tbl_header_0); $i++){
            if ($tbl_header_0[$i]['cols'] > 0){
                $tbl_header_0[$i]['cols'] = $tbl_header_0[$i]['cols'] + 1;
                $tbl_header_1 = $this->insert_array($tbl_header_1,$n,$header_total);

                $nCur = $tbl_header_0[$i]['cols'] + 1;

                $n += $nCur;
            }else{
                $n++;
            }
        }

        $returnsContext = [];
        $savedCheck = 0;
        $addTF = false;
        for($i=0; $i < sizeof($tbl_context); $i++){
            $curArray = $tbl_context[$i];
            if ($curArray['cu_id'] == 0){
                $returnsContext[] = $curArray;
            }else{
                if ($addTF == false && $savedCheck != $curArray['cu_id']){
                    $addTF = true;
                    $returnsContext[] = $curArray;
                    $savedCheck = $curArray['cu_id'];
                }elseif ($addTF == true && $savedCheck == $curArray['cu_id']){
                    $returnsContext[] = $curArray;
                }elseif ($addTF == true && $savedCheck != $curArray['cu_id']){
                    $returnsContext[] = ["sj_id"=>"T","cu_id"=>$savedCheck];
                    $returnsContext[] = $curArray;
                    $addTF = false;
                    $savedCheck = $curArray['cu_id'];
                }else{
                    $returnsContext[] = $curArray;
                }
            }
        }
        if ($savedCheck > 0){
            $returnsContext[] = ["sj_id"=>"T","cu_id"=>$savedCheck];
        }

        return view("sms.index",["testForm"=>$testForm,"data"=>$data,
            "academies"=>$academies, "schoolGrades"=>$schoolGrades, "classes"=>$classes, "testForms"=>$testForms,
            "rAcId"=>$acId,"rGradeId"=>$gradeId,"rClId"=>$classId,"rTfId"=>$tfId,
            "rY"=>$year,"rW"=>$weeks,
            "header0"=>$tbl_header_0,"header1"=>$tbl_header_1,"prerow"=>$tbl_pre_rows,"context"=>$returnsContext,
            "fieldName"=>Configurations::$TEST_FORM_IN_SUBJECT_PREFIX
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

        $data = TestForms::where('ac_id','=',$acId)
            ->where('grade_id','=',$gradeId)
            ->where('class_id','=',$classId)
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
            $data = TestForms::where('ac_id','=',$acId)
                ->where('grade_id','=',$gradeId)
                ->orderBy('form_title','asc')
                ->get();
        }else{
            $data = TestForms::where('ac_id','=',$acId)
                ->orderBy('form_title','asc')
                ->get();
        }


        return response()->json(['data'=>$data]);
    }
}
