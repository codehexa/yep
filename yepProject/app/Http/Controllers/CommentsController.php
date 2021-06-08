<?php

namespace App\Http\Controllers;

use App\Models\Comments;
use App\Models\Configurations;
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
        $data = [];

        $config = Settings::where("set_code","=",Configurations::$SETTINGS_TEST_MAX_SCORE)->first();
        $maxScore = $config->set_value;

        if ($grade != ''){
            $subjects = Subjects::where('sg_id','=',$grade)
                ->orderBy('curri_id','asc')
                ->orderBy('sj_title','asc')
                ->get();
        }else{
            $subjects = Subjects::orderBy('curri_id','asc')
                ->orderBy('sj_title','asc')
                ->get();
        }

        if ($grade != '' && $sjId != ''){
            $data = Comments::where('scg_id','=',$grade)
                ->where('sj_id','=',$sjId)
                ->orderBy('max_score','desc')
                ->get();
        }
        return view("comments.index",[
            "grades"=>$sGrades,'rGrade'=>$grade,'rSjId'=>$sjId,
            'subjects'=>$subjects,
            'data'  => $data,
            "score" => $maxScore
        ]);
    }

    public function setGap(Request $request){
        $sj_id = $request->get("sj_id");
        $sg_id = $request->get("sg_id");
        $gap = $request->get("info_gap");

        $user = Auth::user();

        $config = Settings::where("set_code","=",Configurations::$SETTINGS_TEST_MAX_SCORE)->first();

        $maxScore = $config->set_value;

        $check = Comments::where("scg_id","=",$sg_id)->where("sj_id","=",$sj_id)->count();

        if ($check > 0){
            return redirect()->back()->withErrors(["msg"=>"FAIL_TO_SAVE"]);
        }

        $divided = ceil($maxScore/$gap);

        $tmpGaps = [];
        $tmpGaps[] = $maxScore;
        $nowScore = $maxScore;
        for($i=0; $i < $divided; $i++){
            $nowScore = $nowScore - $gap;

            if ($nowScore < 0) break;
            $tmpGaps[] = $nowScore;
        }

        for ($i=0; $i < sizeof($tmpGaps) -1 ; $i++){
            $max = $tmpGaps[$i];
            $min = $tmpGaps[$i + 1];
            $newComment = new Comments();
            $newComment->scg_id = $sg_id;
            $newComment->sj_id = $sj_id;

            if ($min < 0) $min = 0;
            $newComment->min_score = $min;
            $newComment->max_score = $max;
            $newComment->writer_id = $user->id;
            $newComment->save();
        }

        /* 혹시 모를 최저 점수가 0 이 아닐 경우 처리하기 위함 */
        if ($min > 0){
            $newComment->min_score = 0;
            $newComment->max_score = $min;
            $newComment->writer_id = $user->id;
            $newComment->save();
        }

        return redirect("/comments/".$sg_id."/".$sj_id);

    }

    public function getInfoJson(Request $request){
        $cid = $request->get("cid");

        $data = Comments::find($cid);

        return response()->json(["result"=>"true","data"=>$data]);
    }

    public function storeComment(Request  $request){
        $cmid = $request->get("up_cm_id");
        $sgid = $request->get("up_sg_id");
        $sjid = $request->get("up_sj_id");
        $opinion = $request->get("in_opinion");

        $comment = Comments::find($cmid);

        if (is_null($comment)){
            return redirect()->back()->withErrors(["msg"=>"FAIL_TO_MODIFY"]);
        }else{
            $comment->opinion = $opinion;
            try {
                $comment->save();
                return redirect()->route("comments",["grade"=>$sgid,"sjId"=>$sjid]);
            }catch (\Exception $e){
                return redirect()->back()->withErrors(["msg"=>"FAIL_TO_MODIFY"]);
            }
        }
    }

    public function delete(Request $request){
        $sgId = $request->get("del_sgid");
        $sjId = $request->get("del_sjid");

        $check = Comments::where("scg_id","=",$sgId)
            ->where("sj_id","=",$sjId)->count();
        if ($check <= 0){
            return redirect()->back()->withErrors(["msg"=>"NOTHING_TO_DELETE"]);
        }else{
            try {
                Comments::where("scg_id","=",$sgId)->where("sj_id","=",$sjId)->delete();
                return redirect("/comments/".$sgId."/".$sjId);
            }catch (\Exception $e){
                return redirect()->back()->withErrors(["msg"=>"FAIL_TO_DELETE"]);
            }
        }
    }
}
