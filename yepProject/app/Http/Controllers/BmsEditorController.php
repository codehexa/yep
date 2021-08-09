<?php

namespace App\Http\Controllers;

use App\Models\Academies;
use App\Models\BmsCurriculums;
use App\Models\BmsDays;
use App\Models\BmsPageSettings;
use App\Models\BmsSemesters;
use App\Models\BmsSheetInfo;
use App\Models\BmsSheetInfoItems;
use App\Models\BmsSheets;
use App\Models\BmsStudyTimes;
use App\Models\BmsStudyTypes;
use App\Models\BmsSubjects;
use App\Models\BmsWeeks;
use App\Models\BmsWorkbooks;
use App\Models\BmsYoil;
use App\Models\Classes;
use App\Models\Configurations;
use App\Models\Hakgi;
use App\Models\schoolGrades;
use App\Models\Settings;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Psy\Util\Str;

class BmsEditorController extends Controller
{
    //
    public function __construct(){
        return $this->middleware('auth');
    }

    public function index(){
        $user = Auth::user();
        $hakgiData = BmsSemesters::orderBy('bs_index','asc')->get();
        $academies = Academies::orderBy('ac_name','asc')->get();
        $sgrades = schoolGrades::orderBy('scg_index','asc')->get();
        $classes = Classes::where('teacher_id','=',$user->id)->where('show','=','Y')->orderBy('sg_id','asc')->get();
        $weeksCount = Configurations::$BMS_WEEKS_COUNT;
        $studyTypes = BmsStudyTypes::orderBy('study_type_index','asc')->get();
        $studyTimes = BmsStudyTimes::orderBy('time_index','asc')->get();
        $bmsYoil = BmsYoil::orderBy('yoil_index','asc')->get();
        $teachers = User::where('power','=',Configurations::$USER_POWER_TEACHER)->orderBy('name','asc')->get();
        $curriculums = BmsCurriculums::orderBy('bcur_index','asc')->get();
        $workbooks = BmsWorkbooks::orderBy('bw_index','asc')->get();
        $bmsDays = BmsDays::orderBy('days_index','asc')->get();
        $bmsWeeks = BmsWeeks::orderBy('bmw_index','asc')->get();
        $bmsSubjects = BmsSubjects::orderBy('subject_index','asc')->get();


        return view('bms.editor',[
            'hakgis'=>$hakgiData,'academies'=>$academies,'sgrades'=>$sgrades,'classes'=>$classes,
            'weeksCount'=>$weeksCount,
            'studyTypes'=>$studyTypes,'studyTimes'=>$studyTimes,'bmsDays'=>$bmsDays,'bmsWeeks'=>$bmsWeeks,'bmsYoil'=>$bmsYoil,'curriculums'=>$curriculums,
            'teachers'=>$teachers,'workbooks'=>$workbooks,'subjects'=>$bmsSubjects
        ]);
    }

    // add
    public function addBasic(Request $request){
        $user = Auth::user();

        $shId = $request->get("up_sheet_id");
        $bsId = $request->get("up_semester");
        $acId = $request->get("up_academy");
        $sgId = $request->get("up_school_grade");
        $usId = $request->get("up_teacher");
        $preWeek = $request->get("up_ex_week");
        $nowWeek = $request->get("up_now_week");

        $checkArray = [
            ["bs_id","=",$bsId],["ac_id","=",$acId],["sg_id","=",$sgId],["us_id","=",$usId]
        ];

        if ($shId != ""){
            $check = BmsSheets::find($shId);

            $check->bs_id = $bsId;
            $check->ac_id = $acId;
            $check->sg_id = $sgId;
            $check->us_id = $usId;
            $check->pre_week = $preWeek;
            $check->now_week = $nowWeek;
            $check->writer_id = $user->id;

            return response()->json(['result'=>'true','shId'=>$check->id]);
        }
        $check = BmsSheets::where($checkArray)->count();

        if ($check > 0){
            return response()->json(['result'=>'false','cnt'=>$check]);
        }else{
            $academy = Academies::find($acId);
            $semester = BmsSemesters::find($bsId);
            $schoolGrade = schoolGrades::find($sgId);
            $preWeekVal = BmsWeeks::find($preWeek);
            $nowWeekVal = BmsWeeks::find($nowWeek);

            $_title = $academy->ac_name." / ".$semester->bs_title." / ".$schoolGrade->scg_name." / 이전 주 : ".$preWeekVal->bmw_title." / 이번 주 : ".$nowWeekVal->bmw_title;
            $root = new BmsSheets();
            $root->bs_title = $_title;
            $root->bs_id = $bsId;
            $root->ac_id = $acId;
            $root->sg_id = $sgId;
            $root->us_id = $usId;
            $root->pre_week = $preWeek;
            $root->now_week = $nowWeek;
            $root->writer_id = $user->id;

            try {
                $root->save();
                return response()->json(['result'=>'true','data'=>$root]);
            }catch (\Exception $exception){
                return response()->json(['result'=>'false']);
            }
        }
    }

    public function getClasses(Request $request){
        $acId = $request->get("up_academy");

        $data = Classes::where('ac_id','=',$acId)->where('show','=','Y')->orderBy('class_name','asc')->get();

        return response()->json(['data'=>$data]);
    }

    public function saveSheetItems(Request $request){
        $user = Auth::user();

        $sheetId = $request->get("sheetId");
        $upComment = $request->get("comment");
        $upStudyType = $request->get("std_type");
        $upWorkbook = $request->get("workbook");
        $upClassId = $request->get("classId");
        $upDays = $request->get("days");
        $upStudyTime = $request->get("std_time");
        $upPreWeekFirst = $request->get("pre_week_first");
        $upPreWeekSecond = $request->get("pre_week_second");
        $upClassArray = $request->get("class_array");
        $upSheetIndex = $request->get("cur_class_index");
        $nowWeek = $request->get("now_week");
        $preWeek = $request->get("pre_week");

        // sheet info part
        $whereArray = [
            ["sheet_id","=",$sheetId],
            ["bsi_cls_id","=",$upClassId],
            ["bsi_days","=",$upDays],
            ["bsi_std_times","=",$upStudyTime],
            ["bsi_pre_week","=",$preWeek],
            ["bsi_now_week","=",$nowWeek]
        ];

        if (is_null($sheetId)){
            return response()->json(['result'=>'false','errcode'=>'NO_SHEET_ID']);
        }

        $check = BmsSheetInfo::where($whereArray)->first();

        if (!is_null($check)){
            // update
            $check->sheet_id = $sheetId;
            $check->bsi_comment = $upComment;
            $check->bsi_std_type = $upStudyType;
            $check->bsi_workbook = $upWorkbook;
            $check->bsi_cls_id = $upClassId;
            $check->bsi_days = $upDays;
            $check->bsi_std_times = $upStudyTime;
            $check->bsi_subjects_count = sizeof($upClassArray);
            $check->bsi_pre_subject_1 = $upPreWeekFirst;
            $check->bsi_pre_subject_2 = $upPreWeekSecond;
            $check->writer_id = $user->id;
            //$check->bsi_deleted = "N";
            //$check->bsi_status = Configurations::$BMS_SHEET_INFO_SENT_DATE_READY;
            $check->bsi_sheet_index = $upSheetIndex;
            $check->bsi_pre_week = $preWeek;
            $check->bsi_now_week = $nowWeek;

            $check->save();

            for ($i=0; $i < sizeof($upClassArray); $i++){
                $standArray = ['bms_sheet_id'=>$sheetId,'bms_sheet_info_id'=>$check->id,'bms_shi_index'=>$i];
                $compareArray = [
                    'bms_sii_first_class'=>$upClassArray[$i]["firstClass"],
                    'bms_sii_first_teacher'=>$upClassArray[$i]["firstTeacher"],
                    'bms_sii_second_class'=>$upClassArray[$i]["secondClass"],
                    'bms_sii_second_teacher'=>$upClassArray[$i]["secondTeacher"],
                    'bms_sii_dt'=>$upClassArray[$i]["dt"]
                ];
                $savedSheetItem = BmsSheetInfoItems::updateOrCreate($standArray,$compareArray);
            }
            $del = BmsSheetInfoItems::where('bms_sheet_id','=',$sheetId)
                ->where('bms_sheet_info_id','=',$check->id)
                ->where('bms_shi_index','>=',sizeof($upClassArray))
                ->delete();

            return response()->json(['result'=>'true','shi_id'=>$check->id]);
        }else{
            $new = new BmsSheetInfo();

            $new->sheet_id = $sheetId;
            $new->bsi_comment = $upComment;
            $new->bsi_std_type = $upStudyType;
            $new->bsi_workbook = $upWorkbook;
            $new->bsi_cls_id = $upClassId;
            $new->bsi_days = $upDays;
            $new->bsi_std_times = $upStudyTime;
            $new->bsi_subjects_count = sizeof($upClassArray);
            $new->bsi_pre_subject_1 = $upPreWeekFirst;
            $new->bsi_pre_subject_2 = $upPreWeekSecond;
            $new->writer_id = $user->id;
            $new->bsi_deleted = "N";
            $new->bsi_status = Configurations::$BMS_SHEET_INFO_SENT_DATE_READY;
            $new->bsi_sheet_index = $upSheetIndex;
            $new->bsi_pre_week = $preWeek;
            $new->bsi_now_week = $nowWeek;

            $new->save();

            $bmsSheetInfoId = $new->id;

            for ($i=0; $i < sizeof($upClassArray); $i++){
                $newSheetItem = new BmsSheetInfoItems();
                $newSheetItem->bms_sheet_id = $sheetId;
                $newSheetItem->bms_sheet_info_id = $bmsSheetInfoId;
                $newSheetItem->bms_shi_index = $i;
                $newSheetItem->bms_sii_first_class = $upClassArray[$i]["firstClass"];
                $newSheetItem->bms_sii_first_teacher = $upClassArray[$i]["firstTeacher"];
                $newSheetItem->bms_sii_second_class = $upClassArray[$i]["secondClass"];
                $newSheetItem->bms_sii_second_teacher = $upClassArray[$i]["secondTeacher"];
                $newSheetItem->bms_sii_dt = $upClassArray[$i]["dt"];

                $newSheetItem->save();
            }

            return response()->json(['result'=>'true','shi_id'=>$bmsSheetInfoId]);
        }
    }

    public function loadSheet(Request $request){
        $semester = $request->get("upSemester");
        $acId = $request->get("upAcId");
        $schoolGrade = $request->get("upSchoolGrade");
        $teacher = $request->get("upTeacher");

        $classes = Classes::where('teacher_id','=',$teacher)->where('show','=','Y')->orderBy('class_name','asc')->get();

        $whereArray = [
            ["bs_id",'=',$semester],['ac_id','=',$acId],['sg_id','=',$schoolGrade],['us_id','=',$teacher]
        ];

        $sheets = BmsSheets::where($whereArray)->first();
        if (is_null($sheets)){
            return response()->json(['data'=>[],'classes'=>$classes]);
        }
        $sheet_id = $sheets->id;

        $sheetItems = BmsSheetInfo::where('sheet_id','=',$sheet_id)->orderBy('bsi_sheet_index','asc')->get();

        foreach($sheetItems as $sheetItem){
            $sheetItemInfoId = $sheetItem->id;
            $sheetDays = BmsDays::find($sheetItem->bsi_days);

            $yoilName = $sheetDays->days_title;
            //$yoilName = mb_convert_encoding($yoilName,'UTF-8','UTF-8');

            $children = BmsSheetInfoItems::where("bms_sheet_id","=",$sheet_id)
                ->where("bms_sheet_info_id","=",$sheetItemInfoId)
                ->orderBy('bms_shi_index','asc')
                ->get();

            $ci = 0;
            foreach($children as $child){
                $str = \Illuminate\Support\Str::of($yoilName)->substr($ci,1);
                $ci++;
                $child->setAttribute("dayName",$str);
            }
            $sheetItem->setAttribute('sheetInfoItems',$children);
        }

        return response()->json(['data'=>$sheetItems,'classes'=>$classes]);
    }

    public function preview(Request $request){
        $sheetInfoId = $request->get("saved_sh_info_id");

        $sheetInfo = BmsSheetInfo::find($sheetInfoId);

        // 기본 정보
        $settings = Settings::where('set_code','=',Configurations::$ACADEMY_PRESIDENT_CALL)->first();
        $academy_president_call = $settings->set_value;

        // sheet 에 대한 정보
        $sheet = BmsSheets::find($sheetInfo->sheet_id);
        $academy = $sheet->BmsAcademy;

        // 요일별 내용 들
        $sheetInfoItems = BmsSheetInfoItems::where('bms_sheet_id','=',$sheetInfoId)->orderBy('bms_shi_index','asc')->get();

        $d0 = [];
        $d0[":ACADEMY_NAME:"] = $academy->ac_name;
        $d0[":ACADEMY_ALL_TEL:"]    = $academy_president_call;
        $d0[":ACADEMY_TEL:"] = $academy->ac_tel;

        $sheetProcess = BmsPageSettings::orderBy('field_index','asc')->get();
        if (is_null($sheetProcess)){
            return response()->json(['result'=>'false','code'=>'FIRST_PAGE_SET']);
        }

        $strings = "";  // 표현될 대상 문자열.
        foreach($sheetProcess as $process){
            $str = $process->field_function;
            $typ = $process->field_type;
            if ($typ == ":ADD_BOTTOM_ROW:"){
                $strings .= PHP_EOL;
            }else{
                $strings .= $str;
            }
        }

        foreach ($d0 as $k=>$v){
            $strings = preg_replace('/'.$k.'/i',$v,$strings);
        }

        $functionCodes = Configurations::$BMS_PAGE_FUNCTION_KEYS;

        return response()->json(['txt'=>$strings]);
    }

    public function getFunctions(){

    }
}
