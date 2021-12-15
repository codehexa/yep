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
use Illuminate\Database\Eloquent\Model;
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

    public function index($gradeId =''){
        $academies = Academies::orderBy('ac_name','asc')->get();
        $scGrades = schoolGrades::orderBy('scg_index','asc')->get();

        $config = Settings::where('set_code','=',Configurations::$SETTINGS_PAGE_LIMIT_CODE)->orderBy('id','asc')->get()->first();
        //$config = Settings::where('set_code','=',Configurations::$SETTINGS_PAGE_LIMIT_CODE)->orderBy('id','asc')->latest()->first();
        $limit = $config->set_value;

        $wheres = [];
        if ($gradeId != "") $wheres[] = ["grade_id","=",$gradeId];

        if (sizeof($wheres) > 0){
            $data = TestForms::where('grade_id','=',$gradeId)->orderBy('form_title','asc')->paginate($limit);
            dd($limit);
        }else{
            $data = TestForms::orderBy('form_title','asc')->paginate($limit);
        }


        return view('testforms.index',
            [
                'data'=>$data,'academies'=>$academies,
                'schoolGrades'=>$scGrades,
                'rGradeId'=>$gradeId
            ]
        );
    }

    public function getSubjects(Request $request){
        $grade = $request->get("gradeId");

        $root = Subjects::where('sg_id','=',$grade)
            ->where('depth','=','0')
            ->orderBy('sj_order','asc')
            ->get();

        $subjCtrl = new SubjectsController();

        $data = [];

        foreach ($root as $r){
            if ($r->has_child == "Y"){
                $children = $subjCtrl->getChildren($r->id);
                $sub_subject = [];
                foreach($children as $child){
                    $sub_subject[] = $child->sj_title;
                }
                $r->setAttribute('children',"(".implode("/",$sub_subject).")");
            }else{
                $r->setAttribute('children','');
            }
            $data[] = $r;
        }

        return response()->json(['data'=>$data]);
    }

    public function store(Request $request){
        $infoId = $request->get("info_id");
        $acId = $request->get("up_ac_id");
        $gradeId = $request->get("up_grade_id");

        $name = $request->get("info_name");
        $desc = $request->get("info_desc");
        $exam = $request->get("info_exam");
        $subjectIds = $request->get("tfSavedItems");

        //dd($subjectIds);

        if (is_null($exam)) $exam = "N";

        $user = Auth::user();

        if ($acId == "") $acId = "0";

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
            $newTest->items_count = sizeof($subjectIds);
            $newTest->tf_desc = $desc;
            $newTest->exam = $exam;

            $subjectsCtrl = new SubjectsController();

            try {
                $newTest->save();

                $newTfId = $newTest->id;

                $n = 1;
                foreach ($subjectIds as $subjectId){
                    $nowSubject = Subjects::find($subjectId);
                    $nowTestFormItem = new TestFormsItems();
                    $nowTestFormItem->tf_id = $newTfId;
                    $nowTestFormItem->sj_id = $subjectId;
                    $nowTestFormItem->sj_index = $n;
                    $nowTestFormItem->sj_title = $nowSubject->sj_title;
                    $nowTestFormItem->sj_type = $nowSubject->sj_type;
                    $nowTestFormItem->sj_max_score = $nowSubject->sj_max_score;
                    $nowTestFormItem->sj_parent_id = $nowSubject->parent_id;
                    $nowTestFormItem->sj_depth = $nowSubject->depth;
                    $nowTestFormItem->sj_has_child = $nowSubject->has_child;

                    $nowTestFormItem->save();

                    if ($nowSubject->has_child == "Y"){
                        $nowSubjectChildren = $subjectsCtrl->getChildren($nowSubject->id);
                        $nowSubjectChildrenParentId = $nowTestFormItem->id; // 새로 만들어진 인덱스

                        $n2 = $n+1;
                        foreach ($nowSubjectChildren as $nowSubjectChild){
                            $nowTestFormItem = new TestFormsItems();
                            $nowTestFormItem->tf_id = $newTfId;
                            $nowTestFormItem->sj_id = $nowSubjectChild->id;
                            $nowTestFormItem->sj_index = $n2;
                            $nowTestFormItem->sj_title = $nowSubjectChild->sj_title;
                            $nowTestFormItem->sj_type = $nowSubjectChild->sj_type;
                            $nowTestFormItem->sj_max_score = $nowSubjectChild->sj_max_score;
                            $nowTestFormItem->sj_parent_id = $nowSubjectChildrenParentId;
                            $nowTestFormItem->sj_depth = $nowSubjectChild->depth;
                            $nowTestFormItem->sj_has_child = $nowSubjectChild->has_child;

                            $nowTestFormItem->save();

                            $n2++;
                        }
                        $n = $n2 -1;
                    }
                    $n++;
                }


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
            $savedForm = TestForms::where('id','=',$infoId)->get()->first();
            $oldSubjectsCount = $savedForm->subjects_count;

            // old subject id clear
            /*for ($i=$oldSubjectsCount; $i < Configurations::$TEST_FORM_IN_SUBJECT_MAX; $i++){
                $sjFieldName = Configurations::$TEST_FORM_IN_SUBJECT_PREFIX.$i;
                $savedForm->$sjFieldName = 0;
            }*/

            // 기존의 폼 아이템을 가져온다.
            $savedFormItems = TestFormsItems::where('tf_id','=',$infoId)
                ->where('sj_depth','=','0')->orderBy('sj_index','asc')->get();

            $savedIdsToUpdate = []; // 기존에 저장되어 있는 아이템 배열
            $IdsToAdd = []; // 새로 넣어야 할 아이템 배열
            $IdsToDel = []; // 지워야할 아이템들
            foreach($savedFormItems as $savedFormItem){
                $savedIdsToUpdate[] = $savedFormItem->id;
            }
            for ($i=0; $i < sizeof($subjectIds); $i++){
                //$sjFieldName = Configurations::$TEST_FORM_IN_SUBJECT_PREFIX.$i;
                //$savedForm->$sjFieldName = $subjectIds[$i];
                if (!in_array($subjectIds[$i],$savedIdsToUpdate)){
                    $IdsToAdd[] = $subjectIds[$i];
                }
            }

            for ($j =0; $j < sizeof($savedIdsToUpdate); $j++){
                if (!in_array($savedIdsToUpdate[$j],$subjectIds)){
                    $IdsToDel[] = $savedIdsToUpdate[$j];
                }
            }

            // 삭제하기
//            dd($IdsToDel);
            for ($k=0; $k < sizeof($IdsToDel); $k++){
                $delChildren = TestFormsItems::where('sj_parent_id','=',$IdsToDel[$k])->orderBy('sj_index','desc')->delete();
                $delChildRoot = TestFormsItems::find($IdsToDel[$k])->delete();
            }


            if (sizeof($IdsToAdd) > 0){
                for($i=0; $i < sizeof($IdsToAdd); $i++){
                    //$toAddItemsTestItems = ::find($IdsToAdd[$i]);
                }

            }
            //$savedSubjects = TestFormsItems::where('tf_id','=',$infoId);

            $savedForm->writer_id = $user->id;
            $savedForm->form_title = $name;
            $savedForm->ac_id = $acId;
            $savedForm->grade_id = $gradeId;
            $savedForm->items_count = sizeof($subjectIds);
            $savedForm->tf_desc = $desc;
            $savedForm->exam = $exam;

            //dd($savedForm);
            try {

                $savedForm->save();

                return redirect("/testForm");
            }catch (\Exception $exception){
                dd($exception);
                return redirect()->back()->withErrors(['msg'=>'FAIL_TO_MODIFY']);
            }
        }
    }

    // get info to modify
    public function getTestFormData(Request $request){
        $tfId = $request->get("tfId");

        //$tfForm = TestForms::where('id','=',$tfId)->orderBy('id','asc')->get()->first();  // on centos
        $tfForm = TestForms::where('id','=',$tfId)->orderBy('id','asc')->first();    // on mac
        $tfFormSubjectsRoot = TestFormsItems::where('tf_id','=',$tfId)->
        where('sj_depth','=',0)->orderBy('sj_index','asc')->get();

        //dd($tfFormSubjectsRoot);
        $root = [];
        foreach($tfFormSubjectsRoot as $tfFormSubject){
            if ($tfFormSubject->sj_has_child == "Y"){
                $children = $this->getTestFormItemChildren($tfFormSubject->id);
                $titles = [];
                foreach($children as $child){
                    $titles[] = $child->sj_title;
                }
                $nowTitle = $tfFormSubject->sj_title." (".implode("/",$titles).")";
            }else{
                $nowTitle = $tfFormSubject->sj_title;
            }
            $root[] = ["id"=>$tfFormSubject->id,"title"=>$nowTitle];
        }

        return response()->json(["tfData"=>$tfForm,"tfItems"=>$root,"result"=>"true"]);
    }

    public function getTestFormItemChildren($parent){
        return TestFormsItems::where('sj_parent_id','=',$parent)->orderBy("sj_index","asc")->get();
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

    public function getTestFormsJson(Request $request){
        $grade = $request->get("up_grade_id");

        $data = TestForms::where('grade_id','=',$grade)->orderBy('form_title','asc')->get();

        return response()->json(['data'=>$data]);
    }
}
