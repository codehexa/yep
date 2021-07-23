<?php

namespace App\Http\Controllers;

use App\Models\BmsDays;
use App\Models\BmsStudyTimes;
use App\Models\BmsStudyTypes;
use Illuminate\Http\Request;

class BmsController extends Controller
{
    //
    public function __construct()
    {
        return $this->middleware("auth");
    }

    public function index(){
        return view('bms.index');
    }
    public function settings(){
        $bmsStudyTypes = BmsStudyTypes::orderBy('study_type_index','asc')->get();
        $bmsStudyDays = BmsDays::orderBy('days_index','asc')->get();
        $bmsStudyTimes = BmsStudyTimes::orderBy('time_index','asc')->get();

        return view('bms.settings',[
            "bmsStudyTypes"=>$bmsStudyTypes,"bmsStudyDays"=>$bmsStudyDays,"bmsStudyTimes"=>$bmsStudyTimes
        ]);
    }

    public function addStudyTypesJs(Request $request){
        $name = $request->get("up_name");

        $check = BmsStudyTypes::where('study_title','like',$name)->first();
        if (is_null($check)){
            $count = BmsStudyTypes::count();

            $new = new BmsStudyTypes();
            $new->study_title = $name;
            $new->study_type_index = $count;

            try {
                $new->save();
                return response()->json(['result'=>'true','data'=>$new]);
            }catch (\Exception $exception){
                return response()->json(['result'=>'false']);
            }
        }
    }

    public function saveSortStudyTypes(Request $request){
        $sortData = $request->get("sortData");

        $arr = explode(",",$sortData);
        for ($i=0; $i < sizeof($arr); $i++){
            $d = BmsStudyTypes::find($arr[$i]);
            $d->study_type_index = $i;
            $d->save();
        }

        return response()->json(['result'=>'true']);
    }

    public function saveStudyTypeJs(Request $request){
        $id = $request->get("upId");
        $val = $request->get("upVal");

        $check = BmsStudyTypes::where('study_title','=',$val)->count();
        if ($check > 0)return response()->json(['result'=>'false']);

        $d = BmsStudyTypes::find($id);
        $d->study_title = $val;

        try {
            $d->save();
            return response()->json(['result'=>'true']);
        }catch (\Exception $exception){
            return response()->json(['result'=>'false']);
        }
    }

    public function addStudyDayJs(Request $request){
        $title = $request->get("up_day");

        $check = BmsDays::where('days_title','=',$title)->first();
        if (is_null($check)) {
            $d = new BmsDays();
            $d->days_title = $title;

            $count = BmsDays::count();
            $d->days_index = $count;

            try {
                $d->save();
                return response()->json(['result' => 'true', 'data' => $d]);
            } catch (\Exception $exception) {
                return response()->json(['result' => 'false']);
            }
        }else{
            return response()->json(['result'=>'false']);
        }
    }

    public function saveSortStudyDaysJs(Request $request){
        $list = $request->get("sortData");
        $arr = explode(",",$list);

        for($i=0; $i < sizeof($arr); $i++ ){
            $d = BmsDays::find($arr[$i]);
            $d->days_index = $i;
            $d->save();
        }

        return response()->json(['result'=>'true']);
    }

    public function saveStudyDayJs(Request $request){
        $upId = $request->get("upId");
        $upTxt = $request->get("upTxt");

        $check = BmsDays::where('days_title','=',$upTxt)->count();
        if ($check > 0) return response()->json(['result'=>'false']);

        $d = BmsDays::find($upId);
        $d->days_title = $upTxt;

        try {
            $d->save();
            return response()->json(['result'=>'true']);
        }catch (\Exception $exception){
            return response()->json(['result'=>'false']);
        }
    }

    public function addStudyTimeJs(Request $request){
        $upTxt = $request->get("upTxt");

        $check = BmsStudyTimes::where('time_title','=',$upTxt)->first();
        if (is_null($check)){
            $count = BmsStudyTimes::count();
            $d = new BmsStudyTimes();
            $d->time_title = $upTxt;
            $d->time_index = $count;

            try {
                $d->save();
                return response()->json(['result'=>'true','data'=>$d]);
            }catch (\Exception $exception){
                return response()->json(['result'=>'false']);
            }
        }
        return response()->json(['result'=>'false']);
    }

    public function saveStudyTimeJs(Request $request){
        $upId = $request->get("upId");
        $upTxt = $request->get("upTxt");

        $check = BmsStudyTimes::where('time_title','=',$upTxt)->count();
        if ($check > 0) {
            return response()->json(['result'=>'false']);
        }

        $d = BmsStudyTimes::find($upId);
        $d->time_title = $upTxt;

        try {
            $d->save();
            return response()->json(['result'=>'true']);
        }catch (\Exception $exception){
            return response()->json(['result'=>'false']);
        }

    }

    public function saveSortStudyTimesJs(Request $request){
        $list = $request->get("sortData");

        $arr = explode(",",$list);

        for ($i=0; $i < sizeof($arr); $i++){
            $d = BmsStudyTimes::find($arr[$i]);
            $d->time_index = $i;
            $d->save();
        }

        return response()->json(['result'=>'true']);
    }
}
