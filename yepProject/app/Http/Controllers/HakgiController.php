<?php

namespace App\Http\Controllers;

use App\Models\Configurations;
use App\Models\Hakgi;
use App\Models\schoolGrades;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class HakgiController extends Controller
{
    //
    public function index(){
        $data = Hakgi::orderBy('year','asc')->orderBy('hakgi_name')->get();

        $dataVal = [];

        foreach ($data as $datum){
            $dataVal[] = [
                "id"=>$datum->id,
                "year"  => $datum->year,
                "hakgi_name"    => $datum->hakgi_name,
                "show"  => $datum->show,
                "school_grade_val"  => $this->gradeToPrint($datum->school_grade)
            ];
        }

        $grades = schoolGrades::orderBy('scg_index','asc')->get();
        $gradesArray = [];
        foreach ($grades as $grade){
            $v = [
                "id"=>$grade->id,
                "cName"   => $this->codeGradeToPrint($grade->scg_pre_code). " ".$grade->scg_name
            ];
            $gradesArray[] = $v;
        }

        $data = json_decode(json_encode($dataVal),FALSE);
        $gradesArrayObject = json_decode(json_encode($gradesArray),FALSE);

        return view('hakgi.list',["data"=>$data,"grades"=>$gradesArrayObject]);
    }

    public function codeGradeToPrint($precode){
        $val = "";
        $gradesArray = Configurations::$SCHOOL_PRE_GRADES;
        for ($i=0; $i < sizeof($gradesArray); $i++){
            if ($gradesArray[$i]["value"] == $precode){
                $val = $gradesArray[$i]["name"];
            }
        }

        return $val;
    }

    public function gradeToPrint($grade){
        $data = schoolGrades::find($grade);

        $preCode = $data->scg_pre_code;
        $name = $data->scg_name;
        $val = "";
        $gradesArray = Configurations::$SCHOOL_PRE_GRADES;
        for ($i=0; $i < sizeof($gradesArray); $i++){
            if ($gradesArray[$i]["value"] == $preCode){
                $val = $gradesArray[$i]["name"];
            }
        }

        return $name." ".$val;
    }

    public function add(Request $request){
        $year = $request->get("up_year");
        $schoolGrade = $request->get("up_school_grade");
        $hName = $request->get("up_name");
        $upShow = $request->get("up_show");
        $upCommon = $request->get("up_common");

        $check = Hakgi::where('year','=',$year)->where('hakgi_name','=',$hName)->where('school_grade','=',$schoolGrade)->count();

        if (is_null($upCommon)){
            if ($check > 0){
                return redirect()->back()->withErrors(['msg'=>'FAIL_ALREADY_HAS']);
            }else{
                $hakgi = new Hakgi();
                $hakgi->year = $year;
                $hakgi->school_grade = $schoolGrade;
                $hakgi->hakgi_name = $hName;
                $hakgi->show = $upShow;

                try {
                    $hakgi->save();
                    return redirect()->route("hakgis");
                }catch (\Exception $exception){
                    return redirect()->back()->withErrors(['msg'=>'FAIL_TO_SAVE']);
                }
            }
        }else{
            $schoolGrades = schoolGrades::get();
            foreach($schoolGrades as $sgrade){
                $check2 = Hakgi::where('year','=',$year)
                    ->where('hakgi_name','=',$hName)
                    ->where('school_grade','=',$sgrade->id)
                    ->count();
                if ($check2 <= 0){
                    $newHakgi = new Hakgi();
                    $newHakgi->year = $year;
                    $newHakgi->school_grade = $sgrade->id;
                    $newHakgi->hakgi_name = $hName;
                    $newHakgi->show = $upShow;
                    $newHakgi->save();
                }
            }
            return redirect()->route('hakgis');
        }
    }

    public function getInfo(Request $request){
        $cid = $request->get("cid");

        $data = Hakgi::find($cid);

        return response()->json(['result'=>'true','data'=>$data]);
    }

    public function store(Request $request){
        $upId = $request->get("up_id");
        $upYear = $request->get("up_year");
        $schoolGrade = $request->get("up_school_grade");
        $upName = $request->get("up_name");
        $upShow = $request->get("up_show");

        $root = Hakgi::find($upId);
        $root->year = $upYear;
        $root->hakgi_name = $upName;
        $root->school_grade = $schoolGrade;
        $root->show = $upShow;

        try {
            $root->save();
            return redirect()->route("hakgis");
        }catch (\Exception $e){
            return redirect()->back()->withErrors(["msg"=>"FAIL_TO_SAVE"]);
        }
    }

    public function delete(Request $request){
        $delId = $request->get("del_id");

        $hakgi = Hakgi::find($delId);

        try {
            $hakgi->delete();
            return redirect()->route("hakgis");
        }catch (\Exception $e){
            return redirect()->back()->withErrors(["msg"=>"FAIL_TO_DELETE"]);
        }
    }
}
