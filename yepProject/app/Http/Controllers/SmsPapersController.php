<?php

namespace App\Http\Controllers;

use App\Models\Configurations;
use App\Models\SmsPapers;
use App\Models\TestForms;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SmsPapersController extends Controller
{
    //
    public function delSmsFront(Request $request){
        $delId = $request->get("del_id");

        $data = SmsPapers::find($delId);

        try {
            $data->delete();
            return redirect()->back();
        }catch (\Exception $exception){
            return redirect()->back()->withErrors(["msg"=>"FAIL_TO_DELETE"]);
        }
    }

    public function getTestPapers(Request $request){
        $smsPaper = SmsPapers::find($request->get("sp_id"));
        $nowGradeId = $smsPaper->sg_id;

        $data = TestForms::where('grade_id','=',$nowGradeId)->orderBy('form_title','asc')->get();

        return response()->json(['data'=>$data]);
    }

    public function addSmsPapers(Request $request){
        $spId = $request->get("up_sp_id");
        $testId = $request->get("up_sel_test");

        $oldPaper = SmsPapers::find($spId);

        $newPaper = new SmsPapers();
        $newPaper->writer_id = Auth::user()->id;
        $newPaper->ac_id = $oldPaper->ac_id;
        $newPaper->cl_id = $oldPaper->cl_id;
        $newPaper->sg_id = $oldPaper->sg_id;
        $newPaper->hg_id = $oldPaper->hg_id;
        $newPaper->year = $oldPaper->year;
        $newPaper->week = $oldPaper->week;
        $newPaper->tf_id = $testId;
        $newPaper->sp_code = $oldPaper->sp_code;
        $newPaper->sp_status = Configurations::$SMS_STATUS_READY;

        try {
            $newPaper->save();
            return redirect("/SmsFront");
        }catch (\Exception $exception){
            return redirect()->back()->withErrors(["msg"=>"FAIL_TO_ADD"]);
        }
    }

    public function SmsPaperSetDone(Request $request){
        $spId = $request->get("saved_sp_id");

        $root = SmsPapers::find($spId);
        $root->sp_status = Configurations::$SMS_STATUS_ABLE;

        try {
            $root->save();
            return redirect("/SmsFront");
        }catch (\Exception $exception){
            return redirect()->back()->withErrors(["msg"=>"CANT_SET_DONE"]);
        }
    }

    // 전송가능을 위한 선 점검
    public function SmsCheckToSend(Request $request){
        $spCode = $request->get("spId");

        $checkAll = SmsPapers::where('sp_code','=',$spCode)->get();

        $size = sizeof($checkAll);
        $result = "true";
        if ($size > 0){
            foreach($checkAll as $check){
                if ($check->sp_status != Configurations::$SMS_STATUS_ABLE){
                    $result = "false";
                    break;
                }
            }
        }else{
            $result = "false";
        }

        return response()->json(["result"=>$result]);
    }
}
