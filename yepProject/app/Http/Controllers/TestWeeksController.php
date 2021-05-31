<?php

namespace App\Http\Controllers;

use App\Models\Configurations;
use App\Models\Hakgi;
use App\Models\schoolGrades;
use App\Models\TestWeeks;
use Illuminate\Http\Request;

class TestWeeksController extends Controller
{
    //
    public function list($year='',$hakgi=''){
        $whereArray = [];
        if ($year != ''){
            $whereArray[] = ["year",'=',$year];
        }
        if ($hakgi != ''){
            $whereArray[] = ["hakgi","=",$hakgi];
        }

        $data = TestWeeks::where($whereArray)->orderBy('weeks','asc')->get();

        $sgrades = schoolGrades::orderBy("scg_index","asc")->get();

        $gradesArray = [];

        foreach($sgrades as $grade){
            //$g = ["id"=>$grade->id,"stitle"=>]
            for($i=0; $i < sizeof(Configurations::$SCHOOL_PRE_GRADES); $i++){
                if (Configurations::$SCHOOL_PRE_GRADES[$i]["value"] == $grade->scg_pre_code){
                    $stitle = Configurations::$SCHOOL_PRE_GRADES[$i]["name"];
                    break;
                }
            }
            $gval = $stitle . " " .$grade->scg_name;
            $g = ["id"=>$grade->id,"gname"=>$gval];
            $gradesArray[] = $g;
        }

        $gradesObject = json_decode(json_encode($gradesArray),FALSE);

        return view('testweek.list',["data"=>$data,"sgrades"=>$gradesObject]);
    }

    public function getHakgiListJson(Request $request){
        $hakyon = $request->get("sHaknyon");
        $syear = $request->get("sYear");

        $data = Hakgi::where('year','=',$syear)->where('school_grade','=',$hakyon)->where('show','=','Y')
            ->orderBy('hakgi_name','asc')->get();

        return response()->json(['data'=>$data]);
    }

    public function add(Request $request){
        $year = $request->get("info_year");
        $weeks = $request->get("info_week");
        $context = $request->get("info_context");
        $show = $request->get("info_show");
        $hakgi = $request->get("info_hakgi");

        $check = TestWeeks::where('year','=',$year)
            ->where('weeks','=',$weeks)
            ->where('hakgi','=',$hakgi)
            ->count();

        if ($check > 0){
            return redirect()->back()->withErrors(['msg'=>'FAIL_ALREADY_HAS']);
        }else{
            $tw = new TestWeeks();
            $tw->year = $year;
            $tw->weeks = $weeks;
            $tw->context = $context;
            $tw->show = $show;
            $tw->hakgi = $hakgi;

            try {
                $tw->save();
                return redirect()->route("testweeks");
            }catch (\Exception $e){
                return redirect()->back()->withErrors(['msg'=>'FAIL_TO_SAVE']);
            }
        }
    }
}
