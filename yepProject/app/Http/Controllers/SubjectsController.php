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

        $pageObj = Settings::where('set_code','=',Configurations::$SETTINGS_PAGE_LIMIT_CODE)->first();
        $limit = $pageObj->set_value;

        if ($grade != ''){
            $data = Subjects::where('sg_id','=',$grade)
                ->orderBy("curri_id","asc")
                ->paginate($limit);
        } else {
            $data = Subjects::orderBy("sg_id","asc")
                ->orderBy("curri_id","asc")
                ->paginate($limit);
        }

        $maxScoreObj = Settings::where('set_code','=',Configurations::$SETTINGS_TEST_MAX_SCORE)->first();
        $maxScore = $maxScoreObj->set_value;

        $curris = Curriculums::orderBy('curri_name','asc')->get();

        return view("subjects.index",["data"=>$data,"grades"=>$grades,"curris"=>$curris,"rGrade"=>$grade,"maxScore"=>$maxScore]);
    }

    public function add(Request $request){
        $sgrade = $request->get("info_school_grade_id");
        $curriId = $request->get("info_curri_id");
        $name = $request->get("info_name");
        $score = $request->get("info_score");
        $desc = $request->get("info_desc");
        if ($curriId == '') $curriId = '0';

        $check = Subjects::where('sg_id','=',$sgrade)
            ->where('curri_id','=',$curriId)
            ->where('sj_title','=',$name)->count();

        if ($check > 0){
            return redirect()->back()->withErrors(['msg'=>'FAIL_ALREADY_HAS']);
        }else{
            $subject = new Subjects();
            $subject->sj_title = $name;
            $subject->sj_max_score = $score;
            $subject->sj_desc = $desc;
            $subject->curri_id = $curriId;
            $subject->sg_id = $sgrade;

            try {
                $subject->save();

                $logMode = "add";
                $logTarget = $subject->id;
                $logOld = "";
                $logNew = $subject->id;
                $logField = "";

                $logCtrl = new LogSubjectsController();
                $logCtrl->addLog($logMode,$logTarget,$logOld,$logNew,$logField);

                return redirect()->route("subjects");
            }catch (\Exception $e){
                //dd($e);
                return redirect()->back()->withErrors(['msg'=>'FAIL_TO_SAVE']);
            }
        }
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
        $infoCurri = $request->get("info_curri_id");
        $infoScore = $request->get("info_score");
        $infoDesc = $request->get("info_desc");

        $old = Subjects::find($infoId);

        $subject = Subjects::find($infoId);

        $subject->sj_title = $infoName;
        $subject->sj_max_score = $infoScore;
        $subject->sj_desc = $infoDesc;
        $subject->curri_id = $infoCurri;
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

            if ($old->curri_id != $infoCurri){
                $logOld = $old->curri_id;
                $logNew = $infoCurri;
                $logField = "curriculum";

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

            return redirect()->route("subjects");
        }catch (\Exception $e){
            return redirect()->back()->withErrors(["msg"=>"FAIL_TO_MODIFY"]);
        }
    }

    public function deleteSubject(Request $request){
        $delid = $request->get('del_id');

        $subject = Subjects::find($delid);

        try {
            $subject->delete();

            $logMode = "delete";
            $logTarget = $delid;
            $logOld = $delid;
            $logNew = "";
            $field = "all";

            $logCtrl = new LogSubjectsController();
            $logCtrl->addLog($logMode,$logTarget,$logOld,$logNew,$field);

        }catch (\Exception $exception){
            return redirect()->back()->withErrors(["msg"=>"FAIL_TO_DELETE"]);
        }
    }
}
