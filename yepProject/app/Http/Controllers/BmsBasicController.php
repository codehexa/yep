<?php

namespace App\Http\Controllers;

use App\Models\Academies;
use App\Models\BmsCurriculums;
use App\Models\BmsDays;
use App\Models\BmsDts;
use App\Models\BmsHworks;
use App\Models\BmsPageSettings;
use App\Models\BmsSdl;
use App\Models\BmsSemesters;
use App\Models\BmsSheetInfo;
use App\Models\BmsSheetInfoItems;
use App\Models\BmsStudyBooks;
use App\Models\BmsStudyTimes;
use App\Models\BmsStudyTypes;
use App\Models\BmsSubjects;
use App\Models\BmsWorkbooks;
use App\Models\Classes;
use App\Models\Configurations;
use App\Models\Hakgi;
use App\Models\schoolGrades;
use App\Models\Settings;
use App\Models\Students;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BmsBasicController extends Controller
{
    //
    public function __construct(){
        return $this->middleware('auth');
    }

    public function index(){
        $sGrades = schoolGrades::orderBy('scg_index','asc')->get();
        $academies = Academies::orderBy('ac_name','asc')->get();
        $studyTypes = BmsStudyTypes::orderBy('study_type_index','asc')->get();
        $curriculums = BmsCurriculums::orderBy('bcur_index','asc')->get();
        $days = BmsDays::orderBy('days_index','asc')->get();
        $studyTimes = BmsStudyTimes::orderBy('time_index','asc')->get();
        $studyBooks = BmsStudyBooks::orderBy('bsb_index','asc')->get();
        $sdls = BmsSdl::orderBy('bs_index','asc')->get();
        $workbooks = BmsWorkbooks::orderBy('bw_index','asc')->get();
        $dts = BmsDts::orderBy('id','asc')->get();
        $settingsTel = Settings::where('set_code','=',Configurations::$ACADEMY_PRESIDENT_CALL)->first();

        return view('bms.basic',[
            "sgrades"=>$sGrades,"academies"=>$academies,"presidentCall"=>$settingsTel->set_value,
            "studyTypes"=>$studyTypes,"curriculums"=>$curriculums,"stdDays"=>$days,"stdTimes"=>$studyTimes,
            "studyBooks"=>$studyBooks,"sdls"=>$sdls,"workbooks"=>$workbooks,"dts"=>$dts
        ]);
    }

    public function getTeachers(Request $request){
        $acId = $request->get("up_ac_id");

        $data = User::where('academy_id','=',$acId)->orderBy('name','asc')->get();

        return response()->json(['data'=>$data]);
    }

    public function getHakgis(Request $request){
        $gradeId = $request->get("up_grade_id");

        $data = Hakgi::where('school_grade','=',$gradeId)->where('show','=','Y')->orderBy('id','asc')->get();

        return response()->json(['data'=>$data]);
    }

    public function basicLoadSheet(Request $request){
        $sgrade = $request->get("upSchoolGrade");
        $teacher = $request->get("upTeacher");
        $hakgi = $request->get("upHakgi");
        $acId = $request->get("upAcId");

        $user = Auth::user();
        $classes = Classes::where('teacher_id','=',$teacher)->where('show','=','Y')->orderBy('class_name','asc')->get();

        $subjects = BmsSubjects::where('sg_id','=',$sgrade)->orderBy('subject_index','asc')->get();
        $teachers = User::where('academy_id','=',$acId)->where('power','=',Configurations::$USER_POWER_TEACHER)->orderBy('name','asc')->get();
        $data = [];

        $functions = BmsHworks::where('hwork_sgid','=',$sgrade)->get();

        foreach($classes as $class){
            $classId = $class->id;
            $check = BmsSheetInfo::where('bsi_cls_id','=',$classId)->where('bsi_sgid','=',$sgrade)->first();
            if (isset($check)){
                $infoItems = BmsSheetInfoItems::where('bms_sheet_info_id','=',$check->id)->orderBy('bms_shi_index','asc')->get();
                $check->setAttribute("ShItems",$infoItems);
                $check->setAttribute("class",$class);
                $data[] = $check;
            }else{
                $newData = new BmsSheetInfo();
                $newData->bsi_cls_id = $classId;
                $newData->bsi_sgid = $sgrade;
                $newData->bsi_comment = '';
                $newData->bsi_acid = $class->ac_id;
                $newData->bsi_hakgi = $hakgi;
                $newData->bsi_usid = $teacher;
                $newData->bsi_std_type = 0;
                $newData->bsi_workbook = 0;
                $newData->bsi_days = 0;
                $newData->bsi_std_times = 0;
                $newData->bsi_subjects_count = 0;
                $newData->bsi_pre_subject_1 = 0;
                $newData->bsi_pre_subject_2 = 0;
                $newData->writer_id = $user->id;
                $newData->bsi_deleted = "N";
                $newData->bsi_status = "READY";
                $newData->bsi_sdl   = 0;
                $newData->bsi_sdl_use = 'N';

                $newData->save();


                $newData->setAttribute("ShItems",null);
                $newData->setAttribute("class",$class);
                $data[] = $newData;
            }
        }

        return response()->json(['data'=>$data,'subjects'=>$subjects,'teachers'=>$teachers,'functions'=>$functions]);
    }

    public function saveInfo(Request $request){
        $comment = $request->get("comment");
        $std_type = $request->get("std_type");
        $curriculum = $request->get("curriculum");
        $days = $request->get("days");
        $std_times = $request->get('std_time');
        $sdl_use = $request->get("sdl_use");
        $sdl = $request->get('sdl');
        $workbook_use = $request->get('workbook_use');
        $workbook = $request->get("workbook");
        $studybook_use = $request->get("studybook_use");
        $studybook = $request->get("studybook");
        $dt_use = $request->get('dt_use');
        $dt = $request->get("dt");
        $classId = $request->get("classId");
        $sheetInfoId = $request->get("sheetInfoId");
        $pre_week_first = $request->get('pre_week_first');
        $pre_week_second = $request->get("pre_week_second");
        $classArray = $request->get("class_array");
        $cur_index = $request->get("cur_class_index");
        $now_week = $request->get("now_week");
        $pre_week = $request->get('pre_week');

        $workbook_use_val = "N";
        if (isset($workbook_use)) $workbook_use_val = $workbook_use;

        $studybook_use_val = "N";
        if (isset($studybook_use)) $studybook_use_val = $studybook_use;

        $dt_use_val = "N";
        if (isset($dt_use)) $dt_use_val = $dt_use;

        $sdl_use_val = "N";
        if (isset($sdl_use)) $sdl_use_val = $sdl_use;

        $sheetInfo = BmsSheetInfo::find($sheetInfoId);
        $sheetInfo->bsi_comment = $comment;
        $sheetInfo->bsi_std_type = $std_type;
        $sheetInfo->bsi_workbook = $workbook;
        $sheetInfo->bsi_workbook_use = $workbook_use_val;
        $sheetInfo->bsi_studybook = $studybook;
        $sheetInfo->bsi_studybook_use = $studybook_use_val;
        $sheetInfo->bsi_dt = $dt;
        $sheetInfo->bsi_dt_use = $dt_use_val;
        $sheetInfo->bsi_days = $days;
        $sheetInfo->bsi_curri_id = $curriculum;
        $sheetInfo->bsi_std_times = $std_times;
        $sheetInfo->bsi_sdl = $sdl;
        $sheetInfo->bsi_sdl_use = $sdl_use_val;
        $sheetInfo->bsi_pre_subject_1 = $pre_week_first;
        $sheetInfo->bsi_pre_subject_2 = $pre_week_second;
        $sheetInfo->bsi_sheet_index = $cur_index;
        $sheetInfo->bsi_pre_week = $pre_week;
        $sheetInfo->bsi_now_week = $now_week;

        try {
            $sheetInfo->save();

            for ($i = 0; $i < sizeof($classArray); $i++){
                $checkItem = BmsSheetInfoItems::where('bms_sheet_info_id','=',$sheetInfoId)
                    ->where('bms_shi_index','=',$i)->first();
                if (isset($checkItem)){
                    $checkItem->bms_sii_first_class = $classArray[$i]['firstClass'];
                    $checkItem->bms_sii_first_teacher = $classArray[$i]['firstTeacher'];
                    $checkItem->bms_sii_second_class = $classArray[$i]['secondClass'];
                    $checkItem->bms_sii_second_teacher = $classArray[$i]['secondTeacher'];
                    $checkItem->bms_sii_dt = $classArray[$i]['dt'];
                    $checkItem->bms_sii_dt_direct = $classArray[$i]['dtDirect'];

                    $checkItem->save();
                }else{
                    $newItem = new BmsSheetInfoItems();
                    $newItem->bms_sheet_info_id = $sheetInfoId;
                    $newItem->bms_shi_index = $i;
                    $newItem->bms_sii_first_class = $classArray[$i]['firstClass'];
                    $newItem->bms_sii_first_teacher = $classArray[$i]['firstTeacher'];
                    $newItem->bms_sii_second_class = $classArray[$i]['secondClass'];
                    $newItem->bms_sii_second_teacher = $classArray[$i]['secondTeacher'];
                    $newItem->bms_sii_dt = $classArray[$i]['dt'];
                    $newItem->bms_sii_dt_direct = $classArray[$i]['dtDirect'];

                    $newItem->save();
                }
            }

            return response()->json(['result'=>'true']);
        }catch (\Exception $exception){
            return response()->json(['result'=>'false','code'=>$exception]);
        }
    }

    public function getBasicPageJson(Request $request){
        $wheres = [
            Configurations::$BMS_SMS_TAG_GREETING,
            Configurations::$BMS_SMS_TAG_BOOK_WORK,
            Configurations::$BMS_SMS_TAG_OUTPUT_WORK,
            Configurations::$BMS_SMS_TAG_NOTICE,
            Configurations::$BMS_SMS_TAG_ACADEMY_INFO
        ];

        $data = BmsPageSettings::whereIn('field_tag',$wheres)->orderBy('field_index','asc')->get();

        return response()->json(['data'=>$data]);
    }

    public function getHworkValues(Request $request){
        $d = BmsHworks::where('hwork_class','=',$request->get('bs_id'))->first();

        return response()->json(['data'=>$d]);
    }

    public function getStudentJson(Request $request){
        $clId = $request->get("clId");

        $users = Students::where('class_id','=',$clId)->orderBy('student_name')->get();

        return response()->json(['data'=>$users]);
    }
}
