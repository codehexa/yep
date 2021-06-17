<?php

namespace App\Http\Controllers;

use App\Models\Academies;
use App\Models\Classes;
use App\Models\Configurations;
use App\Models\Curriculums;
use App\Models\schoolGrades;
use App\Models\Settings;
use App\Models\Subjects;
use App\Models\TestForms;
use App\Models\TestFormsItems;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TestFormsController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware("auth");
    }

    public function index($gradeId ='',$acId='',$clId=''){
        $academies = Academies::orderBy('ac_name','asc')->get();
        $scGrades = schoolGrades::orderBy('scg_index','asc')->get();
        $classes = Classes::orderBy('class_name','asc')->get();

        $config = Settings::where('set_code','=',Configurations::$SETTINGS_PAGE_LIMIT_CODE)->first();
        $limit = $config->set_value;

        $wheres = [];
        if ($acId != "") $wheres[] = ["ac_id",'=',$acId];
        if ($gradeId != "") $wheres[] = ["grade_id","=",$gradeId];
        if ($clId != "") $wheres[] = ['class_id','=',$clId];

        if (sizeof($wheres) > 0){
            $data = TestForms::where($wheres)->orderBy('form_title','asc')->paginate($limit);
        }else{
            $data = TestForms::orderBy('form_title','asc')->paginate($limit);
        }

        return view('testforms.index',
            [
                'data'=>$data,'academies'=>$academies,
                'schoolGrades'=>$scGrades,'classes'=>$classes,
                'rAcId'=>$acId,'rGradeId'=>$gradeId,'rClId'=>$clId
            ]
        );
    }

    public function getSubjects(Request $request){
        $grade = $request->get("gradeId");

        $root = DB::table('subjects')
            ->select('subjects.id as sjid','subjects.sj_title as sjtitle','subjects.curri_id as curriid','curriculums.curri_name as curriname')
            ->leftJoin('curriculums','subjects.curri_id','=','curriculums.id')
            ->where('subjects.sg_id','=',$grade)
            ->orderBy('subjects.curri_id','asc')
            ->orderBy('subjects.sj_title','asc')
            ->get();

        $data = [];

        foreach ($root as $r){
            if ($r->curriid != 0){
                $data[] = ["id"=>$r->sjid,"title"=>$r->curriname."_".$r->sjtitle];
            }else{
                $data[] = ["id"=>$r->sjid,"title"=>$r->sjtitle];
            }
        }

        return response()->json(['data'=>$data]);
    }

    public function store(Request $request){
        $infoId = $request->get("info_id");
        $acId = $request->get("up_ac_id");
        $gradeId = $request->get("up_grade_id");
        $clId = $request->get("up_cl_id");

        $name = $request->get("info_name");
        $desc = $request->get("info_desc");
        $subjects = $request->get("tfSavedItems");

        $user = Auth::user();

        if ($acId == "") $acId = "0";
        if ($clId == '') $clId = "0";

        if ($infoId == ""){
            //new
            $checkObj = TestForms::where('grade_id','=',$gradeId)->where('form_title','=',$name)->count();
            if ($checkObj > 0){
                return redirect()->back()->withErrors(['msg'=>'FAIL_ALREADY_HAS']);
            }

            $newTest = new TestForms();
            $newTest->writer_id = $user->id;
            $newTest->form_title = $name;
            $newTest->ac_id = $acId;
            $newTest->grade_id = $gradeId;
            $newTest->class_id = $clId;
            $newTest->subjects_count = sizeof($subjects);
            $newTest->tf_desc = $desc;

            try {
                $newTest->save();

                $newId = $newTest->id;
                //$insertData = [];
                $n = 0;
                foreach ($subjects as $subject){
                    $nowSubject = Subjects::find($subject);
                    //$nowCurri = $nowSubject->curri_id;
                    $subjectFieldName = Configurations::$TEST_FORM_IN_SUBJECT_PREFIX.$n;
                    $newTest->$subjectFieldName = $subject;
                    //$insertData[] = ["tf_id"=>$newId,"curri_id"=>$nowCurri,"sj_id"=>$subject,"tf_index"=>$n];
                    $n++;
                }

                $newTest->save();

                //TestFormsItems::insert($insertData);

                $ltMode = "add";
                $ltTarget = $newTest->id;
                $ltOld = "";
                $ltNew = $newTest->id;
                $ltField = "";

                $tfController = new LogTestFormsController();
                $tfController->addLog($ltMode,$ltTarget,$ltOld,$ltNew,$ltField);

                return redirect("/testForm");

            }catch (\Exception $exception){
                return redirect()->back()->withErrors(['msg'=>'FAIL_TO_SAVE']);
            }
        } else {
            // modify
            $savedForm = TestForms::find($infoId);
            $oldSubjectsCount = $savedForm->subjects_count;

            // old subject id clear
            for ($i=$oldSubjectsCount; $i < Configurations::$TEST_FORM_IN_SUBJECT_MAX; $i++){
                $sjFieldName = Configurations::$TEST_FORM_IN_SUBJECT_PREFIX.$i;
                $savedForm->$sjFieldName = 0;
            }

            for ($i=0; $i < sizeof($subjects); $i++){
                $sjFieldName = Configurations::$TEST_FORM_IN_SUBJECT_PREFIX.$i;
                $savedForm->$sjFieldName = $subjects[$i];
            }
            //$savedSubjects = TestFormsItems::where('tf_id','=',$infoId);

            $savedForm->writer_id = $user->id;
            $savedForm->form_title = $name;
            $savedForm->ac_id = $acId;
            $savedForm->grade_id = $gradeId;
            $savedForm->class_id = $clId;
            $savedForm->subjects_count = sizeof($subjects);
            $savedForm->tf_desc = $desc;

            try {
                $savedForm->save();
                /*
                                $upSubjects = [];
                                for ($i=0; $i < sizeof($subjects); $i++){
                                    $upSubjects[] = $subjects[$i];
                                }

                                $forCheckSubjects = [];

                                foreach($savedSubjects as $savedSubject){
                                    $forCheckSubjects[] = $savedSubject->sj_id;
                                }

                $forDeleteArray = array_diff($forCheckSubjects,$upSubjects);
                $forInsertArray = array_diff($upSubjects,$forCheckSubjects);

                if (sizeof($forDeleteArray > 0)){
                    TestFormsItems::where('tf_id','=',$infoId)->whereIn('sj_id',$forDeleteArray)->delete();
                }

                $insertData = [];
                if (sizeof($forInsertArray)){
                    for ($i=0; $i < sizeof($forInsertArray); $i++){
                        $nowSubject = Subjects::find($forInsertArray[$i]);
                        $nowCurri = $nowSubject->curri_id;
                        $insertData[] = ["tf_id"=>$infoId,"curri_id"=>$nowCurri,"sj_id"=>$nowSubject,"tf_index"=>$i];
                    }

                    TestFormsItems::insert($insertData);
                }
                */
                return redirect("/testForm");
            }catch (\Exception $exception){
                return redirect()->back()->withErrors(['msg'=>'FAIL_TO_MODIFY']);
            }
        }
    }

    // get info to modify
    public function getTestFormData(Request $request){
        $tfId = $request->get("tfId");

        $tfForm = TestForms::find($tfId);
        $tfFormSubjects = [];
        //$root = TestFormsItems::where('tf_id','=',$tfId)->orderBy('tf_index','asc')->get();

        for ($i = 0; $i < $tfForm->subjects_count; $i++){
            $fieldName = Configurations::$TEST_FORM_IN_SUBJECT_PREFIX.$i;
            $tfSubject = $tfForm->$fieldName;
            $subject = Subjects::find($tfSubject);
            $curri_id = $subject->curri_id;
            $curri_name = "";
            if ($curri_id != 0){
                $curri = Curriculums::find($curri_id);
                $curri_name = $curri->curri_name."_";
            }
            $tfFormSubjects[] = ["id"=>$tfSubject,"title"=>$curri_name.$subject->sj_title];
        }
/*
        foreach ($root as $r){
            $crId = $r->curri_id;
            $sjId = $r->sj_id;
            $subject = Subjects::find($sjId);
            if ($crId != 0){
                $curri = Curriculums::find($crId);

                $tfFormSubjects[] = ["id"=>$r->sj_id,"title"=>$curri->curri_name."_".$subject->sj_title];
            }else{
                $tfFormSubjects[] = ["id"=>$r->sj_id,"title"=>$subject->sj_title];
            }
        }*/

        return response()->json(["tfData"=>$tfForm,"tfItems"=>$tfFormSubjects,"result"=>"true"]);
    }

    /* Delete */
    public function deleteForm(Request $request){
        $delId = $request->get("del_id");

        $items = TestFormsItems::where('tf_id','=',$delId)->get();
        $delSjItems = [];
        foreach ($items as $item){
            $delSjItems[] = $item->sj_id;
        }

        try{
            TestFormsItems::where('tf_id','=',$delId)->delete();
            TestForms::where("id","=",$delId)->delete();

            $mode = "delete";
            $target = $delId;
            $old = $delId;
            $new = "0";
            $field = "TestForm";

            $logCtrl = new LogTestFormsController();
            $logCtrl->addLog($mode,$target,$old,$new,$field);

            $mode = "delete";
            $target = $delId;
            $old = implode(",",$delSjItems);
            $new = "0";
            $field = "TestFormItems";

            $logCtrl->addLog($mode,$target,$old,$new,$field);

            return redirect("/testForm");
        }catch (\Exception $exception){
            return redirect()->back()->withErrors(['msg'=>'FAIL_TO_DELETE']);
        }
    }
}
