<?php

namespace App\Http\Controllers;

use App\Models\Comments;
use App\Models\Configurations;
use App\Models\LogComments;
use App\Models\schoolGrades;
use App\Models\Settings;
use App\Models\Subjects;
use App\Models\TestAreas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentsController extends Controller
{
    //
    public function __construct(){
        $this->middleware("auth");
    }

    public function index($grade='',$sjId=''){
        $sGrades = schoolGrades::orderBy('scg_index','asc')->get();
        //dd($sGrades);

        $data = [];

        $config = Settings::where("set_code","=",Configurations::$SETTINGS_TEST_MAX_SCORE)->first();
        $maxScore = $config->set_value;

        $subjectArray = [];

        if ($grade != ''){
            $subjectRoot = Subjects::where('sg_id','=',$grade)
                ->where('depth','=','0')
                ->where('sj_type','=','N')
                ->orderBy('sj_order','asc')
                ->get();

            dd($subjectRoot);

            foreach ($subjectRoot as $subjectR){
                $curParent = $subjectR->id;
                $curhas = $subjectR->has_child;
                if ($curhas == "Y"){
                    $children = $this->getChildren($curParent);
                    foreach($children as $child){
                        $nowId = $child->id;
                        $nowTitle = $subjectR->sj_title. "_" .$child->sj_title;
                        $subjectArray[] = ["id"=>$nowId,"title"=>$nowTitle];
                    }
                }else{
                    $subjectArray[] = ["id"=>$curParent,"title"=>$subjectR->sj_title];
                }
            }
        }

        if ($grade != '' && $sjId != ''){
            $data = Comments::where('scg_id','=',$grade)
                ->where('sj_id','=',$sjId)
                ->orderBy('max_score','desc')
                ->get();
        }
        return view("comments.index",[
            "grades"=>$sGrades,'rGrade'=>$grade,'rSjId'=>$sjId,
            'subjects'=>$subjectArray,
            'data'  => $data,
            "score" => $maxScore
        ]);
    }

    public function getChildren($parent){
        return Subjects::where('parent_id','=',$parent)->where('sj_type','=','N')
            ->orderBy('sj_order','asc')->get();
    }

    public function setComment(Request $request){
        $sjId = $request->get("sj_id");
        $sgId = $request->get('sg_id');
        $minScore = $request->get("up_min_score");
        $maxScore = $request->get("up_max_score");
        $comment = $request->get("up_comments");

        $newComment = new Comments();
        $newComment->scg_id = $sgId;
        $newComment->sj_id = $sjId;
        $newComment->min_score = $minScore;
        $newComment->max_score = $maxScore;
        $newComment->writer_id = Auth::user()->id;
        $newComment->opinion = $comment;

        try {
            $newComment->save();

            $newCommentId = $newComment->id;

            $logMode = "new";
            $target_id = $newCommentId;
            $oldVal = "";
            $newVal = $minScore . " / ".$maxScore."/".$comment;
            $field = "new_comments";

            $logCtrl = new LogCommentsController();
            $logCtrl->addLog($logMode,$target_id,$oldVal,$newVal,$field);

            return redirect("/comments/".$sgId."/".$sjId);
        }catch (\Exception $exception){
            return redirect()->back()->withErrors(['msg'=>'FAIL_TO_SAVE']);
        }
    }

    public function setGap(Request $request){   // 사용하지 않음.
        $sj_id = $request->get("sj_id");
        $sg_id = $request->get("sg_id");
        $gap = $request->get("info_gap");

        $user = Auth::user();

        $subject = Subjects::find($sj_id);
        $maxScore = $subject->sj_max_score;

        $check = Comments::where("scg_id","=",$sg_id)->where("sj_id","=",$sj_id)->count();

        if ($check > 0){
            return redirect()->back()->withErrors(["msg"=>"FAIL_ALREADY_HAS"]);
        }

        $minScore = $maxScore;
        while($minScore > 0){
            $max = $minScore;
            $minScore = $max - $gap;
            $min = $minScore + 1;
            if ($minScore < 0) {
                $min = 0;
                $minScore = 0;
            }
            $newComment = new Comments();
            $newComment->min_score = $min;
            $newComment->max_score = $max;
            $newComment->writer_id = $user->id;
            $newComment->sj_id = $sj_id;
            $newComment->scg_id = $sg_id;
            $newComment->save();
        }

        $logMode = "new";
        $target_id = $sj_id;
        $oldVal = "";
        $newVal = $maxScore . " / ".$gap;
        $field = "new_comments";

        $logCtrl = new LogCommentsController();
        $logCtrl->addLog($logMode,$target_id,$oldVal,$newVal,$field);

        return redirect("/comments/".$sg_id."/".$sj_id);

    }

    public function getInfoJson(Request $request){
        $cid = $request->get("cid");

        $data = Comments::find($cid);

        return response()->json(["result"=>"true","data"=>$data]);
    }

    public function storeComment(Request  $request){
        $cmid = $request->get("cm_id");
        $sgid = $request->get("sg_id");
        $sjid = $request->get("sj_id");
        $minScore = $request->get("up_min_score");
        $maxScore = $request->get("up_max_score");
        $opinion = $request->get("up_comments");

        $comment = Comments::find($cmid);

        $oldComment = Comments::find($cmid);

        if (is_null($comment)){
            return redirect()->back()->withErrors(["msg"=>"FAIL_TO_MODIFY"]);
        }else{
            $comment->opinion = $opinion;
            $comment->min_score = $minScore;
            $comment->max_score = $maxScore;
            $comment->writer_id = Auth::user()->id;

            try {
                $comment->save();

                $logMode = "modify";
                $target_id = $cmid;

                if ($comment->min_score != $oldComment->min_score){
                    $oldVal = $oldComment->min_score;
                    $newVal = $comment->min_score;
                    $field = "min score";

                    $logCtrl = new LogCommentsController();
                    $logCtrl->addLog($logMode,$target_id,$oldVal,$newVal,$field);
                }

                if ($comment->max_score != $oldComment->max_score){
                    $oldVal = $oldComment->max_score;
                    $newVal = $comment->max_score;
                    $field = "max score";

                    $logCtrl = new LogCommentsController();
                    $logCtrl->addLog($logMode,$target_id,$oldVal,$newVal,$field);
                }

                if ($comment->opinion != $oldComment->opinion){
                    $oldVal = $oldComment->opinion;
                    $newVal = $comment->opinion;
                    $field = "Opinion";

                    $logCtrl = new LogCommentsController();
                    $logCtrl->addLog($logMode,$target_id,$oldVal,$newVal,$field);
                }

                return redirect()->route("comments",["grade"=>$sgid,"sjId"=>$sjid]);
            }catch (\Exception $e){
                return redirect()->back()->withErrors(["msg"=>"FAIL_TO_MODIFY"]);
            }
        }
    }

    public function delete(Request $request){
        $delId = $request->get("del_id");

        $check = Comments::find($delId);

        if (is_null($check)){
            return redirect()->back()->withErrors(["msg"=>"NOTHING_TO_DELETE"]);
        }else{
            try {
                Comments::find($delId)->delete();

                $logMode = "delete";
                $target_id = $delId;
                $oldVal = $delId;
                $newVal = "";
                $field = "all_data";

                $logCtrl = new LogCommentsController();
                $logCtrl->addLog($logMode,$target_id,$oldVal,$newVal,$field);

                return redirect("/comments/".$check->scg_id."/".$check->sj_id);
            }catch (\Exception $e){
                return redirect()->back()->withErrors(["msg"=>"FAIL_TO_DELETE"]);
            }
        }
    }
}
