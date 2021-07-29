<?php

namespace App\Http\Controllers;

use App\Models\BmsSubjects;
use App\Models\schoolGrades;
use http\Env\Response;
use Illuminate\Http\Request;

class BmsSubjectsController extends Controller
{
    //
    public function addSubject(Request $request){
        $scgId = $request->get("up_scg_id");
        $subject = $request->get("up_subject");

        $check = BmsSubjects::where([["sg_id","=",$scgId],["subject_title","=",$subject]])->count();

        if ($check > 0){
            return response()->json(['result'=>'false','msg'=>'same data']);
        }else{
            $d = new BmsSubjects();
            $d->sg_id = $scgId;
            $d->subject_title = $subject;

            $cnt = BmsSubjects::where('sg_id','=',$scgId)->count();
            $d->subject_index = $cnt;

            try {
                $d->save();

                $grade = schoolGrades::find($scgId);
                return response()->json(['result'=>'true','data'=>$d,'scgTitle'=>$grade->scg_name]);
            }catch (\Exception $exception){
                return response()->json(['result'=>'false','msg'=>'codeError']);
            }
        }
    }

    public function modifySubject(Request $request){
        $upId = $request->get("upId");
        $upSubject  = $request->get("upSubject");

        $root = BmsSubjects::find($upId);

        $root->subject_title = $upSubject;

        try {
            $root->save();

            return response()->json(['result'=>'true']);
        }catch (\Exception $exception){
            return response()->json(['result'=>'false']);
        }
    }

    public function saveOrder(Request $request){
        $ids = $request->get("bs_ids");

        $arr = explode(",",$ids);
        for($i=0; $i < sizeof($arr);$i++){
            $d = BmsSubjects::find($arr[$i]);
            $d->subject_index = $i;
            $d->save();
        }

        return response()->json(['result'=>'true']);
    }

    public function getSubjects(Request $request){
        $scgId = $request->get("_scgId");

        $scgRoot = schoolGrades::find($scgId);

        $root = BmsSubjects::where('sg_id','=',$scgId)->orderBy('subject_index','asc')->get();

        return response()->json(['data'=>$root,'scgTitle'=>$scgRoot->scg_name]);
    }
}
