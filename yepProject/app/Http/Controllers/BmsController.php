<?php

namespace App\Http\Controllers;

use App\Models\BmsCurriculums;
use App\Models\BmsDays;
use App\Models\BmsStudyTimes;
use App\Models\BmsStudyTypes;
use App\Models\BmsSubjects;
use App\Models\BmsWeeks;
use App\Models\BmsWorkbooks;
use App\Models\schoolGrades;
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
        $bmsCurriculums = BmsCurriculums::orderBy('bcur_index','asc')->get();
        $schoolGrades = schoolGrades::orderBy('scg_index','asc')->get();
        $bmsWorkbooks = BmsWorkbooks::orderBy('bw_index','asc')->get();
        $bmsWeeks = BmsWeeks::orderBy('bmw_index','asc')->get();

        return view('bms.settings',[
            "bmsStudyTypes"=>$bmsStudyTypes,"bmsStudyDays"=>$bmsStudyDays,"bmsStudyTimes"=>$bmsStudyTimes,
            "bmsCurriculums"=>$bmsCurriculums,
            "schoolGrades"=>$schoolGrades,"bmsWorkbooks"=>$bmsWorkbooks,"bmsWeeks"=>$bmsWeeks
        ]);
    }

    public function addStudyTypesJs(Request $request){
        $name = $request->get("up_study_type_title");
        $zoom = $request->get("up_study_type_zoom");

        $check = BmsStudyTypes::where('study_title','like',$name)->first();
        if (is_null($check)){
            $count = BmsStudyTypes::count();

            $new = new BmsStudyTypes();
            $new->study_title = $name;
            $new->study_type_index = $count;
            $new->show_zoom = $zoom;

            try {
                $new->save();
                return response()->json(['result'=>'true','data'=>$new]);
            }catch (\Exception $exception){
                return response()->json(['result'=>'false']);
            }
        }
    }

    public function saveSortStudyTypes(Request $request){
        $sortData = $request->get("_vals");

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
        $zoom = $request->get("upZoom");

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

    public function storeStudyTypeJs(Request $request){
        $id = $request->get("upId");
        $val = $request->get("upVal");
        $zoom = $request->get("upZoom");

        $data = BmsStudyTypes::find($id);

        $data->study_title = $val;
        $data->show_zoom = $zoom;

        try {
            $data->save();
            return response()->json(['result'=>'true']);
        }catch (\Exception $exception){
            return response()->json(['result'=>'false']);
        }
    }

    public function deleteStudyTypeJs(Request $request){
        $dels = $request->get("_dels");

        $delsArray = explode(",",$dels);
        try {
            $data = BmsStudyTypes::whereIn('id',$delsArray)->delete();

            $dataAll = BmsStudyTypes::orderBy('study_type_index','asc')->get();

            $n = 0;

            foreach ($dataAll as $datum){
                $datum->study_type_index = $n;
                $n++;
                $datum->save();
            }
            return response()->json(['result'=>'true']);
        }catch (\Exception $exception){
            return response()->json(['result'=>'false']);
        }

    }

    public function addStudyDayJs(Request $request){
        $title = $request->get("up_days_title");
        $cnt = $request->get("up_days_count");

        $check = BmsDays::where('days_title','=',$title)->first();
        if (is_null($check)) {
            $d = new BmsDays();
            $d->days_title = $title;
            $d->days_count = $cnt;

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

    public function sortStudyDayJs(Request $request){
        $list = $request->get("dels");
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
        $upCnt = $request->get("upCnt");

        $check = BmsDays::where('days_title','=',$upTxt)->count();
        if ($check > 1) return response()->json(['result'=>'false']);

        $d = BmsDays::find($upId);
        $d->days_title = $upTxt;
        $d->days_count = $upCnt;

        try {
            $d->save();
            return response()->json(['result'=>'true']);
        }catch (\Exception $exception){
            return response()->json(['result'=>'false']);
        }
    }

    public function deleteStudyDayJs(Request $request){
        $dels = $request->get("dels");
        $delArray = explode(",",$dels);

        try {
            BmsDays::whereIn('id',$delArray)->delete();
            return response()->json(['result'=>'true']);
        }catch (\Exception $exception){
            return response()->json(['result'=>'false']);
        }
    }

    public function addStudyTimeJs(Request $request){
        $upTxt = $request->get("up_stime_title");

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
        $upId = $request->get("_id");
        $upTxt = $request->get("_title");

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

    public function deleteStudyTimeJs(Request $request){
        $dels = $request->get("_dels");

        $delArray = explode(",",$dels);

        try {
            BmsStudyTimes::whereIn('id',$delArray)->delete();

            return response()->json(['result'=>'true']);
        }catch (\Exception $exception){
            return response()->json(['result'=>'false']);
        }
    }

    public function saveSortStudyTimesJs(Request $request){
        $list = $request->get("_ids");

        $arr = explode(",",$list);

        for ($i=0; $i < sizeof($arr); $i++){
            $d = BmsStudyTimes::find($arr[$i]);
            $d->time_index = $i;
            $d->save();
        }

        return response()->json(['result'=>'true']);
    }


}
