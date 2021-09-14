<?php

namespace App\Http\Controllers;

use App\Imports\ImportStudents;
use App\Models\Academies;
use App\Models\Classes;
use App\Models\Configurations;
use App\Models\LnClassTeacher;
use App\Models\Settings;
use App\Models\Students;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Excel;
use Maatwebsite\Excel\Facades\Excel as ExcelFacade;

class StudentsController extends Controller
{
    //
    public function __construct(){
        $this->middleware("auth");
    }
    public function index($acid='',$clsId=''){
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

        if ($acid != "" && $clsId != ""){
            $data = Students::where("class_id","=",$clsId)->orderBy('student_name','asc')->paginate($pageLimit);
        }elseif ($acid != "" && $clsId == ""){
            $clsArray = Classes::where("ac_id","=",$acid)->get();
            $clsArrayVal = [];
            foreach($clsArray as $cls){
                $clsArrayVal[] = $cls->id;
            }
            $data = Students::whereIn("class_id",$clsArrayVal)->orderBy('student_name','asc')->paginate($pageLimit);
        }else {
            $data = Students::orderBy('student_name','asc')->paginate($pageLimit);
        }

        return view("students.index",[
            "data"  => $data,
            "academies" => $academies,
            "classes"   => $classes,
            "rAcId" => $acid,
            "rClsId" => $clsId
        ]);
    }

    public function fileUpload(Request $request){
        $excelFile = $request->file("up_file_name");
        $acId = $request->get("up_ac_id");
        $clId = $request->get("up_cl_id");
        $tmpName = "ST_".time().".".$excelFile->getClientOriginalExtension();

        if (Storage::disk(Configurations::$EXCEL_FOLDER)->exists($acId."/".$tmpName)){
            return redirect()->back()->withErrors(["msg"=>"FAIL_ALREADY_HAS"]);
        }else{
            $path = $excelFile->storeAs($acId,$tmpName,Configurations::$EXCEL_FOLDER);

            if (!Storage::disk(Configurations::$EXCEL_FOLDER)->path($path)){
                return redirect()->back()->withErrors(["msg"=>"CALL_TO_DEV"]);
            }
            $exFilePath = Storage::disk(Configurations::$EXCEL_FOLDER)->path($path);

            $fMode = "upload";
            $fTarget = "0";
            $fOld = "";
            $fNew = $acId."/".$tmpName;
            $fField = "File";
            $fCtrl = new LogStudentsController();
            $fCtrl->addLog($fMode,$fTarget,$fField,$fOld,$fNew);

            $data = ExcelFacade::toArray(new ImportStudents(), $exFilePath);

            $totalCountInClass = Students::where('class_id','=',$clId)->get();

            $forCheck = []; // 유저 클라스 이동 확인하기 위한 기존 반에 소속된 학생 인덱스 값.
            foreach ($totalCountInClass as $inClass){
                $forCheck[] = $inClass->id;
            }

            for($i=4; $i < sizeof($data[0]); $i++){
                $vals = $data[0][$i];
                $name = $vals[1];
                $tel = $vals[2];
                $hp = $vals[3];
                $parentHp = $vals[4];
                $school = $vals[5];
                $grade = $vals[6];
                $teacher = $vals[10];
                $absCode = $vals[14];

                //dd($vals);

                $check = Students::where('abs_id','=',$absCode)->first();
                if (isset($check)){
                    // new
                    dd("new");
                    $newStudent = new Students();
                    $newStudent->student_name = $name;
                    $newStudent->student_tel = $tel;
                    $newStudent->student_hp = $hp;
                    $newStudent->parent_hp = $parentHp;
                    $newStudent->school_name = $school;
                    $newStudent->school_grade = $grade;
                    $newStudent->abs_id = $absCode;
                    $newStudent->class_id = $clId;
                    $newStudent->teacher_name = $teacher;

                    $newStudent->save();

                    $mode = "add";
                    $target = $newStudent->id;
                    $old = "";
                    $new = trans('strings.str_asb_insert',["ABSCODE"=>$absCode]);
                    $field = "students";

                    $ctrl = new LogStudentsController();
                    $ctrl->addLog($mode,$target,$field,$old,$new);

                }else{
                    $checkStudentId = $check->id;

                    if (($key = array_search($checkStudentId, $forCheck)) !== false){
                        unset($forCheck[$key]);
                    }

                    $hasName = $check->student_name;
                    $hasTel = $check->student_tel;
                    $hasHp = $check->student_hp;
                    $hasParentHp = $check->parent_hp;
                    $hasSchoolName = $check->school_name;
                    $hasSchoolGrade = $check->school_grade;
                    $hasAbsId = $check->abs_id;
                    $hasClassId = $check->class_id;
                    $hasTeacher = $check->teacher_name;

                    if ($hasName != $name){
                        $check->student_name = $name;
                        $mode = "modify";
                        $target = $check->id;
                        $old = $hasName;
                        $new = $name;
                        $field = "name";

                        $ctrl = new LogStudentsController();
                        $ctrl->addLog($mode,$target,$field,$old,$new);
                    }

                    if ($hasTel != $tel){
                        $check->student_tel = $tel;
                        $mode = "modify";
                        $target = $check->id;
                        $old = $hasTel;
                        $new = $tel;
                        $field = "tel";

                        $ctrl = new LogStudentsController();
                        $ctrl->addLog($mode,$target,$field,$old,$new);
                    }

                    if ($hasHp != $hp){
                        $check->student_hp = $hp;
                        $mode = "modify";
                        $target = $check->id;
                        $old = $hasHp;
                        $new = $hp;
                        $field = "Student HP";

                        $ctrl = new LogStudentsController();
                        $ctrl->addLog($mode,$target,$field,$old,$new);
                    }

                    if ($hasParentHp != $parentHp){
                        $check->parent_hp = $parentHp;
                        $mode = "modify";
                        $target = $check->id;
                        $old = $hasParentHp;
                        $new = $parentHp;
                        $field = "Parent HP";

                        $ctrl = new LogStudentsController();
                        $ctrl->addLog($mode,$target,$field,$old,$new);
                    }
                    if ($hasSchoolName != $school){
                        $check->school_name = $school;
                        $mode = "modify";
                        $target = $check->id;
                        $old = $hasSchoolName;
                        $new = $school;
                        $field = "School Name";

                        $ctrl = new LogStudentsController();
                        $ctrl->addLog($mode,$target,$field,$old,$new);
                    }
                    if ($hasSchoolGrade != $grade){
                        $check->school_grade = $grade;
                        $mode = "modify";
                        $target = $check->id;
                        $old = $hasSchoolGrade;
                        $new = $grade;
                        $field = "Grade";

                        $ctrl = new LogStudentsController();
                        $ctrl->addLog($mode,$target,$field,$old,$new);
                    }
                    if ($hasAbsId != $absCode){
                        $check->abs_id = $absCode;
                        $mode = "modify";
                        $target = $check->id;
                        $old = $hasAbsId;
                        $new = $absCode;
                        $field = "ABS CODE";

                        $ctrl = new LogStudentsController();
                        $ctrl->addLog($mode,$target,$field,$old,$new);
                    }
                    if ($hasClassId != $clId){
                        $check->class_id = $clId;
                        $mode = "modify";
                        $target = $check->id;
                        $old = $hasClassId;
                        $new = $clId;
                        $field = "Class ID";

                        $ctrl = new LogStudentsController();
                        $ctrl->addLog($mode,$target,$field,$old,$new);
                    }
                    if ($hasTeacher != $teacher){
                        $check->teacher_name = $teacher;
                        $mode = "modify";
                        $target = $check->id;
                        $old = $hasTeacher;
                        $new = $teacher;
                        $field = "Teacher";

                        $ctrl = new LogStudentsController();
                        $ctrl->addLog($mode,$target,$field,$old,$new);
                    }
                    $check->save();
                }
            } // end for
            Students::whereIn('id',$forCheck)->update(['class_id'=>0]);
            return redirect("/students/".$acId."/".$clId);
        }
    }   // file upload done

    public function getStudentJson(Request $request){
        $stId = $request->get('stId');

        $data = Students::find($stId);

        return response()->json(["result"=>"true","data"=>$data]);
    }

    public function store(Request $request){
        $infoId = $request->get("info_id");
        $acId = $request->get("tmp_ac_id");
        $clId = $request->get("tmp_cl_id");
        $name = $request->get("info_name");
        $tel = $request->get("info_tel");
        $hp = $request->get("info_hp");
        $parentHp = $request->get("info_parent_hp");
        $schoolName = $request->get("info_school_name");
        $grade = $request->get("info_school_grade");
        $abs = $request->get("info_abs_id");
        $classId = $request->get("info_class_id");
        $teacherName = $request->get("info_teacher_name");

        $oldInfo = Students::find($infoId);

        if ($name != $oldInfo->student_name) {
            $lsMode = "modify";
            $lsTarget = $infoId;
            $oldVal = $oldInfo->student_name;
            $newVal = $name;
            $lsField = "name";

            $logCtrl = new LogStudentsController();
            $logCtrl->addLog($lsMode,$lsTarget,$lsField,$oldVal,$newVal);

            $oldInfo->student_name = $name;
        }

        if ($tel != $oldInfo->student_tel) {
            $lsMode = "modify";
            $lsTarget = $infoId;
            $oldVal = $oldInfo->student_tel;
            $newVal = $tel;
            $lsField = "tel";

            $logCtrl = new LogStudentsController();
            $logCtrl->addLog($lsMode,$lsTarget,$lsField,$oldVal,$newVal);

            $oldInfo->student_tel = $tel;
        }

        if ($hp != $oldInfo->student_hp) {
            $lsMode = "modify";
            $lsTarget = $infoId;
            $oldVal = $oldInfo->student_hp;
            $newVal = $hp;
            $lsField = "HP";

            $logCtrl = new LogStudentsController();
            $logCtrl->addLog($lsMode,$lsTarget,$lsField,$oldVal,$newVal);

            $oldInfo->student_hp = $hp;
        }

        if ($parentHp != $oldInfo->parent_hp) {
            $lsMode = "modify";
            $lsTarget = $infoId;
            $oldVal = $oldInfo->parent_hp;
            $newVal = $parentHp;
            $lsField = "Parent HP";

            $logCtrl = new LogStudentsController();
            $logCtrl->addLog($lsMode,$lsTarget,$lsField,$oldVal,$newVal);

            $oldInfo->parent_hp = $parentHp;
        }

        if ($schoolName != $oldInfo->school_name) {
            $lsMode = "modify";
            $lsTarget = $infoId;
            $oldVal = $oldInfo->school_name;
            $newVal = $schoolName;
            $lsField = "School Name";

            $logCtrl = new LogStudentsController();
            $logCtrl->addLog($lsMode,$lsTarget,$lsField,$oldVal,$newVal);

            $oldInfo->school_name = $schoolName;
        }

        if ($grade != $oldInfo->school_grade) {
            $lsMode = "modify";
            $lsTarget = $infoId;
            $oldVal = $oldInfo->school_grade;
            $newVal = $grade;
            $lsField = "School grade";

            $logCtrl = new LogStudentsController();
            $logCtrl->addLog($lsMode,$lsTarget,$lsField,$oldVal,$newVal);

            $oldInfo->school_grade = $grade;
        }

        if ($abs != $oldInfo->abs_id) {
            $lsMode = "modify";
            $lsTarget = $infoId;
            $oldVal = $oldInfo->abs_id;
            $newVal = $abs;
            $lsField = "ABS CODE";

            $logCtrl = new LogStudentsController();
            $logCtrl->addLog($lsMode,$lsTarget,$lsField,$oldVal,$newVal);

            $oldInfo->abs_id = $abs;
        }

        if ($classId != $oldInfo->class_id) {
            $lsMode = "modify";
            $lsTarget = $infoId;
            $oldVal = $oldInfo->class_id;
            $newVal = $classId;
            $lsField = "Class";

            $logCtrl = new LogStudentsController();
            $logCtrl->addLog($lsMode,$lsTarget,$lsField,$oldVal,$newVal);

            $oldInfo->class_id = $classId;
        }

        if ($teacherName != $oldInfo->teacher_name) {
            $lsMode = "modify";
            $lsTarget = $infoId;
            $oldVal = $oldInfo->teacher_name;
            $newVal = $teacherName;
            $lsField = "Teacher name";

            $logCtrl = new LogStudentsController();
            $logCtrl->addLog($lsMode,$lsTarget,$lsField,$oldVal,$newVal);

            $oldInfo->teacher_name = $teacherName;
        }

        try {
            $oldInfo->save();

            if ($clId != "" && $acId != "") {
                return redirect("/students/{$acId}/{$clId}");
            }elseif ($acId != "" && $clId == "") {
                return redirect("/students/{$acId}");
            }elseif ($acId == "" && $clId != ""){
                return redirect("/students");
            }else{
                return redirect("/students");
            }
        }catch (\Exception $e){
            return redirect()->back()->withErrors(["msg"=>"FAIL_TO_MODIFY"]);
        }
    }

    public function testExcel(){
        $fileName = "1/ST_1623135131.xlsx";
        $clId = "1";
        if (Storage::disk(Configurations::$EXCEL_FOLDER)->exists($fileName)){
            $excelFile = Storage::disk(Configurations::$EXCEL_FOLDER)->path($fileName);
            $data = ExcelFacade::toArray(new ImportStudents(), $excelFile);

            for($i=4; $i < sizeof($data[0]); $i++){
                $vals = $data[0][$i];
                $name = $vals[1];
                $tel = $vals[2];
                $hp = $vals[3];
                $parentHp = $vals[4];
                $school = $vals[5];
                $grade = $vals[6];
                $teacher = $vals[10];
                $absCode = $vals[14];

                $d = [
                        'student_name' => $name,
                        'student_tel'   => $tel,
                        'student_hp'    => $hp,
                        'parent_hp' => $parentHp,
                        'school_name'   => $school,
                        'school_grade'  => $grade,
                        'abs_id'    => $absCode,
                        'class_id'  => $clId,
                        'teacher_name'  => $teacher
                    ];
                Students::updateOrCreate(
                    $d
                );
            }
        }
    }

    public function getClasses(Request $request){
        $acid = $request->get("acId");

        $data = Classes::where('ac_id','=',$acid)->orderBy('class_name','asc')->get();

        return response()->json(['data'=>$data]);
    }
}
