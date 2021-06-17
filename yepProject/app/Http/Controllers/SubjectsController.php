<?php

namespace App\Http\Controllers;

use App\Models\Configurations;
use App\Models\Curriculums;
use App\Models\schoolGrades;
use App\Models\Settings;
use App\Models\Subjects;
use Illuminate\Http\Request;

class SubjectsController extends Controller
{
    //
    public function index($grade=''){
        $grades = schoolGrades::orderBy('scg_index','asc')->get();

        $data = [];
        if ($grade != ''){
            $parents = Subjects::where('sg_id','=',$grade)
                ->where('depth','=',0)
                ->orderBy('sj_order','asc')
                ->get();

            foreach ($parents as $parent){
                $hasChild = $parent->has_child;
                $children = [];
                if ($hasChild == "Y"){
                    $children = $this->getChildren($parent->id);
                }
                $parent->setAttribute('children',$children);
                $data[] = $parent;
            }
        }

        $maxScoreObj = Settings::where('set_code','=',Configurations::$SETTINGS_TEST_MAX_SCORE)->first();
        $maxScore = $maxScoreObj->set_value;

        return view("subjects.index",["data"=>$data,"grades"=>$grades,"rGrade"=>$grade,"maxScore"=>$maxScore]);
    }

    public function getChildren($parent_id){
        return Subjects::where('parent_id','=',$parent_id)
            ->orderBy('sj_order','asc')->get();
    }

    public function add(Request $request){
        $sgrade = $request->get("info_school_grade_id");
        $name = $request->get("info_name");
        $score = $request->get("info_score");
        $desc = $request->get("info_desc");
        $parent = $request->get("info_parent");
        $depth = $request->get("info_depth");
        $hasChild = $request->get("info_has_child");

        $check = Subjects::where('sg_id','=',$sgrade)
            ->where('sj_title','=',$name)
            ->where('parent_id','=',$parent)
            ->where('depth','=',$depth)
            ->count();

        if ($check > 0){
            return redirect()->back()->withErrors(['msg'=>'FAIL_ALREADY_HAS']);
        }else{
            $subject = new Subjects();
            $subject->sj_title = $name;
            $subject->sj_max_score = $score;
            $subject->sj_desc = $desc;
            $subject->sg_id = $sgrade;
            $subject->parent_id = $parent;
            $subject->depth = $depth;
            $subject->has_child = $hasChild;

            $indexing = $this->getChildrenIndex($parent,$sgrade);
            $indexing_val = $indexing + 1;
            $subject->sj_order = $indexing_val;

            try {
                $subject->save();

                if ($parent > 0){
                    $parentSubject = Subjects::find($parent);
                    $parentSubject->has_child = "Y";
                    $parentSubject->save();
                }

                $logMode = "add";
                $logTarget = $subject->id;
                $logOld = "";
                $logNew = $subject->id;
                $logField = "";

                $logCtrl = new LogSubjectsController();
                $logCtrl->addLog($logMode,$logTarget,$logOld,$logNew,$logField);

                return redirect("/subjects/{$sgrade}");
            }catch (\Exception $e){
                //dd($e);
                return redirect()->back()->withErrors(['msg'=>'FAIL_TO_SAVE']);
            }
        }
    }

    public function getChildrenIndex($parent,$sgrade){
        return Subjects::where('parent_id','=',$parent)->where('sg_id','=',$sgrade)->count();
    }

    public function addGroup(Request $request){
        $group = $request->get("up_group_title");

        $check = Curriculums::where('curri_name','=',$group)->count();

        if ($check > 0){
            return response()->json(["result"=>"false","msg"=>"FAIL_ALREADY_HAS"]);
        }else{
            $add = new Curriculums();
            $add->curri_name = $group;

            try {
                $add->save();

                $logMode = "addGroup";
                $logTarget = $add->id;
                $logOld = "";
                $logNew = $add->id;
                $logField = "Curriculum";

                $logCtrl = new LogSubjectsController();
                $logCtrl->addLog($logMode,$logTarget,$logOld,$logNew,$logField);

                return response()->json(["result"=>"true","data"=>$add]);
            }catch (\Exception $e){
                return response()->json(["result"=>"false","msg"=>"FAIL_TO_SAVE"]);
            }
        }
    }

    public function getSubjectJson(Request $request){
        $cId = $request->get("cid");

        $subject = Subjects::find($cId);

        return response()->json(["result"=>"true","data"=>$subject]);
    }

    public function store(Request $request){
        $infoId = $request->get("info_id");
        $infoName = $request->get("info_name");
        $infoSchoolId = $request->get("info_school_grade_id");
        $infoScore = $request->get("info_score");
        $infoDesc = $request->get("info_desc");

        $old = Subjects::find($infoId);

        $subject = Subjects::find($infoId);

        $subject->sj_title = $infoName;
        $subject->sj_max_score = $infoScore;
        $subject->sj_desc = $infoDesc;
        $subject->sg_id = $infoSchoolId;

        try {
            $subject->save();

            $logMode = "modify";
            $logTarget = $infoId;

            //dd($old->sj_title);

            if ($old->sj_title != $infoName){
                $logOld = $old->sj_title;
                $logNew = $infoName;
                $logField = "title";

                $logCtrl = new LogSubjectsController();
                $logCtrl->addLog($logMode,$logTarget,$logOld,$logNew,$logField);
            }

            if ($old->sj_max_score != $infoScore){
                $logOld = $old->sj_max_score;
                $logNew = $infoScore;
                $logField = "score";

                $logCtrl = new LogSubjectsController();
                $logCtrl->addLog($logMode,$logTarget,$logOld,$logNew,$logField);
            }

            if ($old->sj_desc != $infoDesc){
                $logOld = $old->sj_desc;
                $logNew = $infoDesc;
                $logField = "desc";

                $logCtrl = new LogSubjectsController();
                $logCtrl->addLog($logMode,$logTarget,$logOld,$logNew,$logField);
            }

            if ($old->sg_id != $infoSchoolId){
                $logOld = $old->sg_id;
                $logNew = $infoSchoolId;
                $logField = "school_grade";

                $logCtrl = new LogSubjectsController();
                $logCtrl->addLog($logMode,$logTarget,$logOld,$logNew,$logField);
            }

            return redirect("/subjects/{$infoSchoolId}");
        }catch (\Exception $e){
            return redirect()->back()->withErrors(["msg"=>"FAIL_TO_MODIFY"]);
        }
    }

    public function deleteSubject(Request $request){
        $delid = $request->get('del_id');

        $subject = Subjects::find($delid);

        $old_parent = $subject->parent_id;
        $old_gradeId = $subject->sg_id;
        $old_depth = $subject->depth;
        $old_has_child = $subject->has_child;
        $old_order = $subject->sj_order;

        try {
            $subject->delete();

            if ($old_has_child == "Y"){
                Subjects::where('parent_id','=',$delid)->delete();
            }
            Subjects::where('parent_id','=',$old_parent)->where('sj_order','>',$old_order)->where('sg_id','=',$old_gradeId)
                ->decrement('sj_order',1);

            $logMode = "delete";
            $logTarget = $delid;
            $logOld = $delid;
            $logNew = "";
            $field = "all";

            $logCtrl = new LogSubjectsController();
            $logCtrl->addLog($logMode,$logTarget,$logOld,$logNew,$field);

            return redirect("/subjects/{$old_gradeId}");

        }catch (\Exception $exception){
            //dd($exception);

            return redirect()->back()->withErrors(["msg"=>"FAIL_TO_DELETE"]);
        }
    }

    public function updateOrderSubjects(Request $request){
        $orders = $request->get("orders");
        $orderArray = explode(",",$orders);
        $subjects = Subjects::whereIn('id',$orderArray)->get();
        $n = 1;
        foreach($subjects as $subject){
            $subject->sj_order = $n;
            $subject->save();
            $n++;
        }

        return response()->json(["result"=>"true"]);
    }
}
