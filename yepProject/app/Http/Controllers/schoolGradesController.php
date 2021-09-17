<?php

namespace App\Http\Controllers;

use App\Models\Configurations;
use App\Models\schoolGrades;
use Illuminate\Http\Request;

class schoolGradesController extends Controller
{
    //
    public function __construct(){
        $this->middleware('adminPower');
    }
    public function index(){
        $data = schoolGrades::orderBy('scg_index','asc')->get();

        $grades = json_decode(json_encode(Configurations::$SCHOOL_PRE_GRADES),FALSE);

        return view('pages.school_grades',["data"=>$data,"grades"=>$grades]);
    }

    public function add(Request $request){
        $gName = $request->get("up_new_name");
        $gNotSet = $request->get("up_new_not_set");

        $schoolGrade = new schoolGrades();
        $schoolGrade->scg_name = $gName;
        $schoolGrade->scg_not_set = $gNotSet;

        $preCount = schoolGrades::orderBy('scg_index','desc')->count();

        $nowCount = $preCount + 1;
        $schoolGrade->scg_index = $nowCount;

        try {
            $schoolGrade->save();
            return redirect()->route("schoolGrades");
        }catch (\Exception $e){
            return redirect()->back()->withErrors(["msg"=>"FAIL_TO_SAVE"]);
        }

    }

    public function move(Request $request){
        $did = $request->get("did");
        $mode = $request->get("dmode");

        $current = schoolGrades::find($did);

        $current_index = $current->scg_index;

        if ($mode == "UP"){
            if ($current_index <= 1){
                return redirect()->route("schoolGrades");
            }else{
                $before_index = $current_index -1;
                $beforeObj = schoolGrades::where("scg_index","=",$before_index)->first();
                $beforeObj->scg_index = $current_index;
                $beforeObj->save();

                $current->scg_index = $before_index;
                $current->save();

                return redirect()->route("schoolGrades");
            }
        }else{
            // DOWN
            $nextObj = schoolGrades::where('scg_index','=',$current_index + 1)->first();
            if (is_null($nextObj)){
                return redirect()->route("schoolGrades");
            }else{
                $current->scg_index = $current_index +1;
                $current->save();
                $nextObj->scg_index = $current_index;
                $nextObj->save();

                return redirect()->route("schoolGrades");
            }
        }
    }

    public function info(Request $request){
        $did = $request->get("did");
        $data = schoolGrades::find($did);

        return response()->json(["result"=>"true","data"=>$data]);
    }

    public function store(Request $request){
        $edId = $request->get("ed_id");
        $grade = $request->get("up_new_name");
        $notSet = $request->get("up_new_not_set");

        $data = schoolGrades::find($edId);
        $data->scg_name = $grade;
        $data->scg_not_set = $notSet;

        $data->save();

        return redirect()->route("schoolGrades");
    }

    public function delete(Request $request){
        $did = $request->get("did");

        $data = schoolGrades::find($did);

        $data->delete();

        return redirect()->route("schoolGrades");
    }
}
