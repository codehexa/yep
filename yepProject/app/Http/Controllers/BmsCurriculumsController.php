<?php

namespace App\Http\Controllers;

use App\Models\BmsCurriculums;
use Illuminate\Http\Request;

class BmsCurriculumsController extends Controller
{
    //
    public function addCurri(Request $request){
        $cTitle = $request->get("up_curriculum");

        $check = BmsCurriculums::where('bcur_title','=',$cTitle)->count();

        if ($check <= 0){
            $new = new BmsCurriculums();
            $new->bcur_title = $cTitle;

            $count = BmsCurriculums::count();
            $new->bcur_index = $count;

            try{
                $new->save();
                return response()->json(['result'=>'true','data'=>$new]);
            }catch (\Exception $exception){
                return response()->json(['result'=>'false']);
            }
        }else{
            return response()->json(['result'=>'false']);
        }
    }

    public function saveCurriculum(Request $request){
        $id = $request->get("cId");
        $title = $request->get("cTitle");

        $check = BmsCurriculums::where('bcur_title','=',$title)->count();
        if ($check > 0){
            return response()->json(['result'=>'false','msg'=>'has_pre_title']);
        }else{
            $d = BmsCurriculums::find($id);

            $d->bcur_title = $title;

            try {
                $d->save();
                return response()->json(['result'=>'true']);
            }catch (\Exception $exception){
                return response()->json(['result'=>'false','msg'=>'save_false']);
            }
        }
    }

    public function saveSortCurriculumsJs(Request $request){
        $ids = $request->get("sortData");

        $arr = explode(",",$ids);

        for ($i=0; $i < sizeof($arr); $i++){
            $d = BmsCurriculums::find($arr[$i]);
            $d->bcur_index = $i;
            $d->save();
        }

        return response()->json(['result'=>'true']);
    }
}
