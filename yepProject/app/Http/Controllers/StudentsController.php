<?php

namespace App\Http\Controllers;

use App\Models\Academies;
use App\Models\Classes;
use App\Models\Configurations;
use App\Models\LnClassTeacher;
use App\Models\Settings;
use App\Models\Students;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StudentsController extends Controller
{
    //
    public function __construct(){
        $this->middleware("auth");
    }
    public function index($year='',$acid='',$clsId=''){
        $user = Auth::user();

        $nowAcId = $user->academy_id;

        $userPower = $user->power;

        if ($userPower != Configurations::$USER_POWER_ADMIN){
            $academies = Academies::where("id","=",$nowAcId)->orderBy('ac_name','asc')->get();
            if ($userPower == Configurations::$USER_POWER_TEACHER){
                $classes = Classes::where("ac_id","=",$nowAcId)
                    ->where('teacher_id',$user->id)
                    ->orderBy('class_name','asc')->get();
            }else{
                $classes = Classes::where("ac_id","=",$nowAcId)->orderBy('class_name','asc')->get();
            }
        }else{
            $academies = Academies::orderBy("ac_name","asc")->get();
            $classes = Classes::orderBy("class_name","asc")->get();
        }

        $optObj = Settings::where('set_code','=',Configurations::$SETTINGS_PAGE_LIMIT_CODE)->first();
        $pageLimit = $optObj->set_value;
        $data = Students::orderBy('student_name','asc')->paginate($pageLimit);

        return view("students.index",[
            "data"  => $data,
            "academies" => $academies,
            "classes"   => $classes,
            "rYear" => $year,
            "rAcId" => $acid,
            "rClsId" => $clsId
        ]);
    }
}
