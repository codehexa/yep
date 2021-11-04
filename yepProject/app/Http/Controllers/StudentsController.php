<?php

namespace App\Http\Controllers;

use App\Imports\ImportStudents;
use App\Models\Academies;
use App\Models\Classes;
use App\Models\Configurations;
use App\Models\LnClassTeacher;
use App\Models\schoolGrades;
use App\Models\Settings;
use App\Models\Students;
use App\Models\User;
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

    public function studentsSearch($field='',$key=''){
        $user = Auth::user();

        $nowAcId = $user->academy_id;

        $userPower = $user->power;

        $isTeacher = "Y";

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
            $isTeacher = "N";
            $academies = Academies::orderBy("ac_name","asc")->get();
            $classes = Classes::orderBy("class_name","asc")->get();
        }

        $optObj = Settings::where('set_code','=',Configurations::$SETTINGS_PAGE_LIMIT_CODE)->first();
        $pageLimit = $optObj->set_value;

        if ($field != "" && $key != "") {
            if ($isTeacher == "N") {
                $data = Students::where($field, "like", "%{$key}%")->orderBy('student_name', 'asc')->paginate($pageLimit);
            } else {
                $inArray = [];
                foreach ($classes as $cls) {
                    $inArray . push($cls->id);
                }
                $data = Students::whereIn("class_id", $inArray)->where($field, "like", "%{$key}%")->orderBy('student_name', 'asc')->paginate($pageLimit);
            }
        }else{
            $data = [];
        }

        return view("students.index",[
            "data"  => $data,
            "academies" => $academies,
            "classes"   => $classes,
            "rAcId" => "",
            "rClsId" => "",
            "field" => $field,
            "key"   => $key
        ]);
    }

    public function fileUpload(Request $request){
        $excelFile = $request->file("up_file_name");
        $acId = $request->get("up_ac_id");
        $clId = $request->get("up_cl_id");
        $tmpName = "ST_".time().".".$excelFile->getClientOriginalExtension();
        $classRoot = [];

        $academies = new Academies();
        $acHash = $academies->getHashTable();
        $classes = new Classes();
        $clHash = $classes->getHashTable();
        $users = new User();
        $teacherHash = $users->getTeacherHashTable();
        $students = new Students();
        $allAbsIds = $students->getAllAbsCodes();

        $schoolGrades = new schoolGrades();
        $not_set_grade = $schoolGrades->getNotSet();    // school_grades.scg_not_set = "Y"

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

            //$totalCountInClass = Students::where('class_id','=',$clId)->get();

            /*$forCheck = []; // 유저 클라스 이동 확인하기 위한 기존 반에 소속된 학생 인덱스 값.
            foreach ($totalCountInClass as $inClass){
                $forCheck[] = $inClass->id;
            }*/

            for($i=4; $i < sizeof($data[0]); $i++){
                $vals = $data[0][$i];
                $name = $vals[1];
                $tel = $vals[2];
                $hp = $vals[3];
                $parentHp = $vals[4];
                $school = $vals[5];
                $grade = $vals[6];
                $clRoot = $vals[7];
                $teacher = $vals[10];
                $absCode = $vals[14];

                // class name
                $clRoot1 = substr($clRoot,0,strpos($clRoot,Configurations::$EXCEL_CLASS_RIP_CODE)); // excel 반명 추출. 학원코드 + 반코드
                $clAcCode = substr($clRoot1,0,1);
                $clCode = substr($clRoot1,1,strlen($clRoot1));

                $nowAcId = "";

                if (isset($acHash[$clAcCode])){
                    $nowAcId = $acHash[$clAcCode];  // academies.id
                }

                if ($nowAcId == "") $nowAcId = $acId;

                // 선생님 찾기
                $teacherName = substr($teacher,0,strpos($teacher,Configurations::$EXCEL_CLASS_RIP_CODE));   // 선생님 이름만
                $teacherAcCode = substr($teacher,strpos($teacher,Configurations::$EXCEL_CLASS_RIP_CODE) + 1,1); // 학원 코드
                $teacherAcId = $acHash[$teacherAcCode]; // 선생님이 속한 학원 아이디

                $tmpTeacherKey = $teacherName."_".$teacherAcId;
                $nowTeacherId = "";    // 선생님 users.id
                if (!isset($teacherHash[$tmpTeacherKey])){
                    $nowTeacherId = $users->makeNewTeacher($teacherName,$teacherAcId);
                    $teacherHash[$teacherName."_".$teacherAcId] = $nowTeacherId;
                }else{
                    $nowTeacherId = $teacherHash[$tmpTeacherKey];
                }

                if (isset($clHash[$clAcCode.$clCode])){
                    $nowClId = $clHash[$clAcCode.$clCode];
                }else{
                    $nowClId = $classes->newClass($clRoot1,$nowAcId,$nowTeacherId,$not_set_grade);
                    $clHash[$clRoot1] = $nowClId;
                }

                //$cnt = Students::where('abs_id','=',$absCode)->count();
                //dd($allAbsIds);
                $cnt = in_array($absCode,$allAbsIds);

                if (!$cnt && $absCode != null){
                    // new

                    $newStudent = new Students();
                    $newStudent->student_name = $name;
                    $newStudent->student_tel = $tel;
                    $newStudent->student_hp = $hp;
                    $newStudent->parent_hp = $parentHp;
                    $newStudent->school_name = $school;
                    $newStudent->school_grade = $grade;
                    $newStudent->abs_id = $absCode;
                    $newStudent->class_id = $nowClId;
                    $newStudent->teacher_name = $teacherName;
                    $newStudent->ac_id = $nowAcId;

                    $newStudent->save();

                    $mode = "add";
                    $target = $newStudent->id;
                    $old = "";
                    $new = trans('strings.str_asb_insert',["ABSCODE"=>$absCode]);
                    $field = "students";

                    $ctrl = new LogStudentsController();
                    $ctrl->addLog($mode,$target,$field,$old,$new);

                }elseif ($cnt && $absCode != null){
                    $check = Students::where('abs_id','=',$absCode)->first();

                    $hasName = $check->student_name;
                    $hasTel = $check->student_tel;
                    $hasHp = $check->student_hp;
                    $hasParentHp = $check->parent_hp;
                    $hasSchoolName = $check->school_name;
                    $hasSchoolGrade = $check->school_grade;
                    $hasAbsId = $check->abs_id;
                    $hasClassId = $check->class_id;
                    $hasTeacher = $check->teacher_name;
                    $hasAcId = $check->ac_id;

                    if ($hasAcId != $nowAcId){
                        $check->ac_id = $nowAcId;
                        $mode = "modify";
                        $target = $check->id;
                        $old = $hasAcId;
                        $new = $nowAcId;
                        $field = "academy";

                        $ctrl = new LogStudentsController();
                        $ctrl->addLog($mode,$target,$field,$old,$new);
                    }

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
                    if ($hasClassId != $nowClId){
                        $check->class_id = $nowClId;
                        $mode = "modify";
                        $target = $check->id;
                        $old = $hasClassId;
                        $new = $nowClId;
                        $field = "Class ID";

                        $ctrl = new LogStudentsController();
                        $ctrl->addLog($mode,$target,$field,$old,$new);
                    }
                    if ($hasTeacher != $teacherName){
                        $check->teacher_name = $teacherName;
                        $mode = "modify";
                        $target = $check->id;
                        $old = $hasTeacher;
                        $new = $teacherName;
                        $field = "Teacher";

                        $ctrl = new LogStudentsController();
                        $ctrl->addLog($mode,$target,$field,$old,$new);
                    }
                    $check->save();
                }
            } // end for
            return redirect("/students");
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
