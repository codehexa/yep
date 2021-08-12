<?php

namespace App\Http\Controllers;

use App\Models\Academies;
use App\Models\BmsCurriculums;
use App\Models\BmsDays;
use App\Models\BmsSemesters;
use App\Models\BmsSheetInfo;
use App\Models\BmsSheetInfoItems;
use App\Models\BmsStudyTimes;
use App\Models\BmsStudyTypes;
use App\Models\Classes;
use App\Models\Hakgi;
use App\Models\schoolGrades;
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

        return view('bms.basic',[
            "sgrades"=>$sGrades,"academies"=>$academies,
            "studyTypes"=>$studyTypes,"curriculums"=>$curriculums,"stdDays"=>$days,"stdTimes"=>$studyTimes
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

        $user = Auth::user();
        $classes = Classes::where('teacher_id','=',$teacher)->where('show','=','Y')->orderBy('class_name','asc')->get();

        $data = [];

        foreach($classes as $class){
            $classId = $class->id;
            $check = BmsSheetInfo::where('bsi_cls_id','=',$classId)->where('bsi_sgid','=',$sgrade)->first();
            if (isset($check)){
                $infoItems = BmsSheetInfoItems::where('bms_sheet_info_id','=',$check->id)->orderBy('bms_shi_index','asc')->get();
                $check->setAttribute("items",$infoItems);
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

                $newData->save();

                $newData->setAttribute("items",[]);
                $newData->setAttribute("class",$class);
                $data[] = $newData;
            }
        }

        return response()->json(['data'=>$data]);
    }
}
