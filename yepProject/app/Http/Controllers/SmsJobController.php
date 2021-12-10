<?php

namespace App\Http\Controllers;

use App\Exports\TestExcelExport;
use App\Models\Academies;
use App\Models\Classes;
use App\Models\Configurations;
use App\Models\Curriculums;
use App\Models\Hakgi;
use App\Models\schoolGrades;
use App\Models\Settings;
use App\Models\SmsPageSettings;
use App\Models\SmsPapers;
use App\Models\SmsScores;
use App\Models\SmsSendResults;
use App\Models\Students;
use App\Models\Subjects;
use App\Models\TestForms;
use App\Models\TestFormsItems;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class SmsJobController extends Controller
{
    //
    public function __construct(){
        $this->middleware("auth");
    }

    public function front($acId='',$gradeId='',$clId='',$year='',$hakgi='',$week=''){
        $user = Auth::user();
        $nowPower = $user->power;
        $myClassesIds = [];

        if ($acId === "ALL") $acId = "";
        if ($gradeId === "ALL") $gradeId = "";
        if ($clId === "ALL") $clId = "";
        if ($year === "ALL") $year = "";
        if ($hakgi === "ALL") $hakgi = "";
        if ($week === "ALL") $week = "";

        if ($nowPower === Configurations::$USER_POWER_TEACHER){
            $acId = $user->academy_id;
        }

        //dd("acid :".$acId." / grade : {$gradeId} / clID : {$clId} / year: {$year} / hakgi : {$hakgi} / week : {$week}");

        if ($nowPower != Configurations::$USER_POWER_ADMIN){
            $academies = Academies::where('id','=',$user->academy_id)->orderBy('ac_name','asc')->get();
        }else{
            $academies = Academies::orderBy('ac_name','asc')->get();
        }

        $schoolGrades = schoolGrades::orderBy('scg_index','asc')->get();

        if ($nowPower == Configurations::$USER_POWER_TEACHER){
            $myClasses = Classes::where('teacher_id','=',$user->id)->get();

            foreach ($myClasses as $myClass){
                $myClassesIds[] = $myClass->id;
            }
            $classes = Classes::where('teacher_id','=',$user->id)->get();
        }else{
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
        }


        $dataWhere = [];
        if ($acId != "") {
            $dataWhere[] = ["ac_id","=",$acId];
        }
        if ($gradeId != ""){
            $dataWhere[] = ["sg_id","=",$gradeId];
        }
        if ($clId != "") {
            $dataWhere[] = ["cl_id",'=',$clId];
        }
        if ($year != ""){
            $dataWhere[] = ["year",'=',$year];
        }
        if ($hakgi != ""){
            $dataWhere[] = ["hg_id",'=',$hakgi];
        }
        if ($week != ""){
            $dataWhere[] = ["week","=",$week];
        }

        $settings = Settings::where('set_code','=',Configurations::$SETTINGS_PAGE_LIMIT_CODE)->first();
        $limit = $settings->set_value;

        if ($nowPower == Configurations::$USER_POWER_TEACHER){
            if ($acId != "" && $clId != ""){
                $data = SmsPapers::where($dataWhere)->paginate($limit);
            }else{
                $data = SmsPapers::where('ac_id','=',$acId)
                    ->whereIn('cl_id',$myClassesIds)
                    ->paginate($limit);
            }
        }else{
            $data = SmsPapers::where($dataWhere)->paginate($limit);
        }


        $RHakgis = [];

        if ($year != "" && $gradeId != ""){
            $RHakgis = Hakgi::where('school_grade','=',$gradeId)->where('show','=','Y')->orderBy('hakgi_name','asc')->get();
        }

        $MaxWeeks = 0;
        if ($hakgi != ""){
            $hakgiWeeks = Hakgi::find($hakgi);
            $MaxWeeks = $hakgiWeeks->weeks;
        }

        return view("sms.front",["data"=>$data,
            "academies"=>$academies,"schoolGrades"=>$schoolGrades,"classes"=>$classes,
            "RacId"=>$acId,"RsgId"=>$gradeId,"RclId"=>$clId,"RhgId"=>$hakgi,"Ryear"=>$year,"Rweek"=>$week,
            "RHakgis"=>$RHakgis,"MaxWeeks"=>$MaxWeeks
        ]);
    }

    public function index($acId='',$gradeId='',$classId='',$tfId='',$year='',$hakgi='',$weeks=''){

        $user = Auth::user();
        $nowPower = $user->power;

        $data = [];
        $tItems = [];
        $testForms = [];
        $hakgiMax = 0;
        $hakgiData = [];

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

        if ($acId != ""  && $gradeId != "" && $classId != "" && $tfId != "" && $year != "" && $hakgi != "" &&  $weeks != ""){
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

            $hakgiData = Hakgi::where('school_grade','=',$gradeId)
                ->where('show','=','Y')->orderBy('hakgi_name','asc')->get();
            $hakgiDatum = Hakgi::find($hakgi);
            $hakgiMax = $hakgiDatum->weeks;

            $students = Students::where("class_id","=",$classId)->where('is_live','=','Y')->get();

            foreach ($students as $student){
                $stId = $student->id;
                $checkScore = SmsScores::where('sg_id','=',$gradeId)
                    ->where('year','=',$year)
                    ->where('week','=',$weeks)
                    ->where('tf_id','=',$tfId)
                    ->where('st_id','=',$stId)
                    ->where('cl_id','=',$classId)
                    ->where('hg_id','=',$hakgi)
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
                    $newSmsScore->hg_id = $hakgi;
                    $newSmsScore->opinion = "";
                    $newSmsScore->wordian = "";
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

        return view("sms.index",["testForms"=>$testForms,"testForm"=>$testForm,"tItems"=>$tItems,"data"=>$data,"hasDouble"=>$hasDouble,
            "academies"=>$academies, "schoolGrades"=>$schoolGrades, "classes"=>$classes,
            "rAcId"=>$acId,"rGradeId"=>$gradeId,"rClId"=>$classId,"rTfId"=>$tfId,
            "rY"=>$year,"indexN"=>"0",
            "hakgis"=>$hakgiData,"rHakgi"=>$hakgi,"maxHakgi"=>$hakgiMax,"rW"=>$weeks,
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
        $year = $request->get("section_year");

        $user = Auth::user();

        if ($user->power == Configurations::$USER_POWER_TEACHER){
            $data = Classes::where('teacher_id','=',$user->id)->where('sg_id','=',$gradeId)->orderBy('class_name','asc')->get();
        } else {
            if ($gradeId != ""){
                $data = Classes::where('ac_id','=',$acId)
                    ->where('sg_id','=',$gradeId)
                    ->orderBy('class_name','asc')->get();
            }else{
                $data = Classes::where('ac_id','=',$acId)->orderBy('class_name','asc')->get();
            }
        }


        $hakgi = new \stdClass();

        if ($year != ""){
            $hakgi = Hakgi::where('school_grade','=',$gradeId)
                ->where('show','=','Y')
                ->orderBy('hakgi_name','asc')->get();
        }


        return response()->json(['data'=>$data,'hakgi'=>$hakgi]);
    }

    public function getTestFormsJson(Request $request){
        $acId = $request->get("section_academy");
        $gradeId = $request->get("up_grade_id");

        if (is_null($acId)) $acId = 0;

        $data = [];

        if ($gradeId != ""){
            $root = TestForms::where('grade_id','=',$gradeId)
                ->orderBy('form_title','asc')
                ->get();
            // whereIn('ac_id',[$acId,'0'])
        }else{
            $root = TestForms::orderBy('form_title','asc')
                ->get();
        }

        foreach ($root as $r){
            $rid = $r->id;
            $children = $this->getChildrenTest($rid);
            $r->subjects = $children;
            $data[] = $r;
        }

        return response()->json(['data'=>$data]);
    }

    public function getChildrenTest($id){
        return TestFormsItems::where('tf_id','=',$id)->where('sj_depth','=',0)->orderBy("sj_index","asc")->get();
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

    public function saveWordian(Request $request){
        $nowId = $request->get("wordian_info_id");
        $wordian = $request->get("info_wordian");

        $result = "false";

        $Score = SmsScores::find($nowId);

        $Score->wordian = $wordian;

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
        $wordian = $request->get("wordian");
        $sendReady = $request->get("sready");

        $score = SmsScores::find($scId);
        $score->opinion = $opinion;
        $score->wordian = $wordian;
        $score->send_ready = $sendReady;

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
        //dd($request);
        /*
         * code making. 000 + code + 0000
         */
        $spCode = $request->get("up_sp_code");
        $sendSpCode = rand(100,999).$spCode.rand(1000,9999);
        $sendHexCode = bin2hex($sendSpCode);

        $smsPageSetting = SmsPageSettings::orderBy('id','asc')->first();
        $smsMsg = $smsPageSetting->sps_opt_1;
        $smsURL = Configurations::$SMS_PAGE_URL.$sendHexCode;

        $spPapers = SmsPapers::where('sp_code','=',$spCode)->where('sp_status','=',Configurations::$SMS_STATUS_ABLE)->get();

        $send_N = 0;
        foreach($spPapers as $spPaper){
            $classId = $spPaper->cl_id;
            $tfId = $spPaper->tf_id;
            $year = $spPaper->year;
            $week = $spPaper->week;

            $students = Students::where('class_id','=',$classId)->where('is_live','=','Y')->get();
            foreach ($students as $student){
                $smsReadyCheck = SmsScores::where('year','=',$year)
                    ->where('week','=',$week)
                    ->where('tf_id','=',$tfId)
                    ->where('st_id','=',$student->id)
                    ->where('send_ready','=','Y')
                    ->count();
                if ($smsReadyCheck > 0){
                    $smsReMsg = str_replace(Configurations::$SMS_REPLACE_NAME,$student->student_name,$smsMsg);
                    $smsReMsg .= $smsURL;
                    $newSmsSend = new SmsSendResults();
                    $newSmsSend->student_id = $student->id;
                    $newSmsSend->class_id = $classId;
                    $newSmsSend->sms_paper_code = $spCode;
                    $newSmsSend->sms_msg = $smsReMsg;
                    $newSmsSend->ssr_status = Configurations::$SMS_SEND_RESULTS_READY;
                    $newSmsSend->sms_tel_no = $student->parent_hp;
                    $newSmsSend->ssr_view = Configurations::$SMS_SEND_VIEW_N;
                    $newSmsSend->save();
                    $send_N++;
                    if ($student->student_hp != ""){
                        $newSmsSend = new SmsSendResults();
                        $newSmsSend->student_id = $student->id;
                        $newSmsSend->class_id = $classId;
                        $newSmsSend->sms_paper_code = $spCode;
                        $newSmsSend->sms_msg = $smsReMsg;
                        $newSmsSend->ssr_status = Configurations::$SMS_SEND_RESULTS_READY;
                        $newSmsSend->sms_tel_no = $student->student_hp;
                        $newSmsSend->ssr_view = Configurations::$SMS_SEND_VIEW_N;
                        $newSmsSend->save();
                    }
                }
            }
            $spPaper->sp_status = Configurations::$SMS_STATUS_SENT;
            $spPaper->sent_date = now();
            $spPaper->save();
        }

        return view('sms.send_result',['total'=>$send_N]);
    }

    public function getHakgisHandler(Request $request){
        $gradeId = $request->get("grade_id");
        $year = $request->get("year");

        $data = Hakgi::where('school_grade','=',$gradeId)
            ->where('show','=','Y')
            ->orderBy('hakgi_name','asc')
            ->get();

        return response()->json(['data'=>$data]);
    }

    public function saveMatch(Request $request){
        //dd($request);
        $acId = $request->get("up_academy");
        $clIdRoot = $request->get("up_class");  // 배열. , 로 구분 가능.
        $sgId = $request->get("up_grade");
        $hgId = $request->get("up_hakgi");
        $year = $request->get("up_year");
        $week = $request->get("up_week");
        $tfIds = $request->get("tf_ids");   // array
        $tfIdsString = implode(",",$tfIds);

        $clIds = explode(",",$clIdRoot);

        for ($j=0; $j < sizeof($clIds); $j++){
            // check
            $clId = $clIds[$j];
            $wheres = [
                ["ac_id",'=',$acId],
                ["cl_id",'=',$clId],
                ["sg_id",'=',$sgId],
                ["hg_id",'=',$hgId],
                ["year",'=',$year],
                ["week",'=',$week]
            ];
            $oldValues = SmsPapers::where($wheres)->whereIn('tf_id',[$tfIdsString])->get();

            if (sizeof($oldValues) > 0){
                $saved_tf_ids = [];
                $savedSpCode = "";
                $sentValue = "";
                foreach($oldValues as $preDatum){
                    $saved_tf_ids[] = $preDatum->tf_id;
                    $savedSpCode = $preDatum->sp_code;
                    $sentValue = $preDatum->sp_status;
                }

                if ($sentValue != Configurations::$SMS_STATUS_READY){
                    if ($sentValue == Configurations::$SMS_STATUS_SAVING){
                        return redirect()->back()->withErrors(["msg"=>"FAIL_CAUSE_SAVING"]);
                    }
                    if ($sentValue == Configurations::$SMS_STATUS_SENT){
                        return redirect()->back()->withErrors(["msg"=>"FAIL_CAUSE_SENT"]);
                    }
                }
                $compares = array_diff($tfIds,$saved_tf_ids);   // $tfIds 에 있는 요소만 남는다. 삭제할 요소
                $add_compares = array_diff($saved_tf_ids,$tfIds);   // 입력해야할 요소.
                // 삭제할 것.
                for($i=0; $i < sizeof($compares); $i++){
                    SmsPapers::where($wheres)->where('tf_id','=',$compares[$i])->delete();
                }

                for ($i=0; $i < sizeof($add_compares); $i++){
                    $newItem = new SmsPapers();
                    $newItem->writer_id = Auth::user()->id;
                    $newItem->ac_id = $acId;
                    $newItem->cl_id = $clId;
                    $newItem->sg_id = $sgId;
                    $newItem->hg_id = $hgId;
                    $newItem->year = $year;
                    $newItem->week = $week;
                    $newItem->tf_id = $add_compares[$i];
                    $newItem->sp_code = $savedSpCode;
                    $newItem->sp_status = Configurations::$SMS_STATUS_READY;
                    $newItem->save();
                }
            }else{
                // new insert
                $spCode = $this->getSmsPaperCode();
                for ($i=0; $i < sizeof($tfIds); $i++){
                    $nPaper = new SmsPapers();
                    $nPaper->writer_id = Auth::user()->id;
                    $nPaper->ac_id = $acId;
                    $nPaper->cl_id = $clId;
                    $nPaper->sg_id = $sgId;
                    $nPaper->hg_id = $hgId;
                    $nPaper->year = $year;
                    $nPaper->week = $week;
                    $nPaper->tf_id = $tfIds[$i];
                    $nPaper->sp_code = $spCode;
                    $nPaper->sp_status = Configurations::$SMS_STATUS_READY;
                    $nPaper->save();
                }
            }
        }
        return redirect("/SmsFront/{$acId}/{$sgId}/{$clIds[0]}/{$year}/{$hgId}/{$week}");
    }

    public function getSmsPaperCode(){
        return substr(str_shuffle(str_repeat("0123456789abcdefghijklmnopqrstuvwxyz", 10)), 0, 10);
    }


    public function SmsJobInput($spId){
        $smsPaper = SmsPapers::find($spId);
        $acId = $smsPaper->ac_id;
        $clId = $smsPaper->cl_id;
        $sgId = $smsPaper->sg_id;
        $hgId = $smsPaper->hg_id;
        $year = $smsPaper->year;
        $week = $smsPaper->week;
        $tfId = $smsPaper->tf_id;

        $hasDouble = "N";
        $data = [];

        $formCtrl = new TestFormsController();
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

        $students = Students::where("class_id","=",$clId)
            ->where('is_live','=','Y')->orderBy('student_name','asc')->get();

        foreach ($students as $student){
            $stId = $student->id;
            $checkScore = SmsScores::where('sg_id','=',$sgId)
                ->where('year','=',$year)
                ->where('week','=',$week)
                ->where('tf_id','=',$tfId)
                ->where('st_id','=',$stId)
                ->where('cl_id','=',$clId)
                ->where('hg_id','=',$hgId)
                ->first();

            if (is_null($checkScore)){
                $newSmsScore = new SmsScores();
                $newSmsScore->sg_id = $sgId;
                $newSmsScore->writer_id = Auth::user()->id;
                $newSmsScore->year = $year;
                $newSmsScore->week = $week;
                $newSmsScore->tf_id = $tfId;
                $newSmsScore->st_id = $stId;
                $newSmsScore->cl_id = $clId;
                $newSmsScore->hg_id = $hgId;
                $newSmsScore->opinion = "";
                $newSmsScore->sent = "N";
                $newSmsScore->score_count = sizeof($tItems);
                for ($i=0; $i < sizeof($tItems); $i++){
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
        }

        //dd($data);
        return view("sms.input",["paperInfo"=>$smsPaper,"spId"=>$spId,"testForm"=>$testForm,"tItems"=>$tItems,"data"=>$data,"hasDouble"=>$hasDouble]);
    }

    public function saveSmsJob(Request $request){
        // smsjobinput all save click/
        //dd($request);
        $smsPaper = SmsPapers::find($request->get("saved_sp_id"));
        $autoSave = $request->get("saved_auto");

        $ssIds = $request->get("ss_id");

        $testFormItemsCount = TestFormsItems::where('tf_id','=',$smsPaper->tf_id)->where('sj_has_child','=','N')->count();
        //dd($testFormItemsCount); 9
        $opinionVals = $request->get("ss_opinion");
        $wordianVals = $request->get("ss_wordian");
        $sendReady = $request->get("sready");

        //dd($sendReady);


        if (!is_null($ssIds)){
            for($i=0; $i < sizeof($ssIds); $i++){   // $ssId smsscores.id
                //dd($ssIds[$i]);
                $nowSmsScore = SmsScores::find($ssIds[$i]);

                for ($j=0; $j < $testFormItemsCount; $j++){
                    $fieldName = Configurations::$TEST_SCORES_FIELD_PREFIX.$j;
                    $nowRequest = $request->get($fieldName);
                    //dd($nowRequest[$i]);
                    $nowScore = $nowRequest[$i];
                    //dd($nowScore);
                    $nowSmsScore->$fieldName = $nowScore;
                }
                $sendReadyEach = "Y";
                if (!isset($sendReady[$i]) || $sendReady[$i] != "Y"){
                    $sendReadyEach = "N";
                }

                $nowSmsScore->send_ready = $sendReadyEach;
                $nowSmsScore->opinion = $opinionVals[$i];
                $nowSmsScore->wordian = $wordianVals[$i];
                $nowSmsScore->save();
            }
            if ($autoSave == "Y"){
                $smsPaper->sp_status = Configurations::$SMS_STATUS_ABLE;
            }else{
                $smsPaper->sp_status = Configurations::$SMS_STATUS_SAVING;
            }

            try {
                $smsPaper->save();
            }catch (\Exception $exception){
                return redirect()->back()->withErrors(["msg"=>"FAIL_TO_SAVE"]);
            }
        }
        return redirect("/SmsFront");
    }

    // excel download
    public function SmsExcelDownload($ppId){
        // 프린트용 템플릿은 views/exports/papers.blade.php 사용
        // 데이터 클래스는 app/Exports/TestExcelExport.php 사용
        $paper = SmsPapers::find($ppId);
        $testform = TestForms::find($paper->tf_id);
        $classInfo = Classes::find($paper->cl_id);
        $academyInfo = Academies::find($paper->ac_id);

        $excelFilename = date("Ymd").$academyInfo->ac_name."_".$classInfo->class_name."_".$paper->year."_".$paper->week."_".$testform->form_title.".xlsx";
        return Excel::download(new TestExcelExport($ppId),$excelFilename);
    }

    // temp for preview
    public function tempGet(Request $request){
        $pId = $request->get("pId");

        $paper = SmsPapers::find($pId);

        return response()->json(['spcode'=>$paper->sp_code]);

    }
}
