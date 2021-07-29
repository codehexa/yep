<?php

namespace App\Http\Controllers;

use App\Models\Academies;
use App\Models\BmsCurriculums;
use App\Models\BmsStudyTimes;
use App\Models\BmsStudyTypes;
use App\Models\BmsYoil;
use App\Models\Classes;
use App\Models\Configurations;
use App\Models\Hakgi;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BmsEditorController extends Controller
{
    //
    public function __construct(){
        return $this->middleware('auth');
    }

    public function index(){
        $user = Auth::user();
        $hakgiData = Hakgi::where('show','=','Y')->orderBy('school_grade','asc')->orderBy('id','asc')->get();
        $academies = Academies::orderBy('ac_name','asc')->get();
        $classes = Classes::where('teacher_id','=',$user->id)->where('show','=','Y')->orderBy('sg_id','asc')->get();
        $weeksCount = Configurations::$BMS_WEEKS_COUNT;
        $studyTypes = BmsStudyTypes::orderBy('study_type_index','asc')->get();
        $studyTimes = BmsStudyTimes::orderBy('time_index','asc')->get();
        $bmsYoil = BmsYoil::orderBy('yoil_index','asc')->get();
        $teachers = User::where('power','=',Configurations::$USER_POWER_TEACHER)->orderBy('name','asc')->get();
        $curriculums = BmsCurriculums::orderBy('bcur_index','asc')->get();


        return view('bms.editor',[
            'hakgis'=>$hakgiData,'academies'=>$academies,'classes'=>$classes,
            'weeksCount'=>$weeksCount,
            'studyTypes'=>$studyTypes,'studyTimes'=>$studyTimes,'bmsYoil'=>$bmsYoil,'curriculums'=>$curriculums,
            'teachers'=>$teachers
        ]);
    }
}
