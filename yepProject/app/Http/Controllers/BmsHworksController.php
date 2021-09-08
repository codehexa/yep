<?php

namespace App\Http\Controllers;

use App\Models\BmsHworks;
use App\Models\BmsSubjects;
use App\Models\Configurations;
use App\Models\schoolGrades;
use App\Models\Settings;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class BmsHworksController extends Controller
{
    //
    public function __construct(){
        return $this->middleware('auth');
    }

    public function index(){
        $schoolGrades = schoolGrades::orderBy("scg_index","asc")->get();
        $codes = Configurations::$BMS_PAGE_FUNCTION_KEYS;
        $collections = new Collection();
        foreach ($codes as $pf){
            $collections->push((object)$pf);
        }

        return view("bms.hworks",[
            "schoolGrades"=>$schoolGrades,'codes'=>$collections
        ]);
    }

    public function addClass(Request $request){
        $sgId = $request->get("up_sgid");
        $upClass = $request->get("up_class");
        $upContent = $request->get("up_content");
        $upDt = $request->get("up_dt");
        $upBooks = $request->get("up_book");
        $upOutputFirst = $request->get("up_output_first");
        $upOutputSecond = $request->get("up_output_second");

        $check = BmsHworks::where([["hwork_sgid",'=',$sgId],['hwork_class','=',$upClass]])->count();

        if ($check > 0){
            return redirect()->back()->withErrors(['msg'=>'FAIL_ALREADY_HAS']);
        }else{
            $d = new BmsHworks();
            $d->hwork_class = $upClass;
            $d->hwork_sgid = $sgId;
            $d->hwork_content = $upContent;
            $d->hwork_dt = $upDt;
            $d->hwork_book = $upBooks;
            $d->hwork_output_first = $upOutputFirst;
            $d->hwork_output_second = $upOutputSecond;

            try {
                $d->save();

                return redirect("/bms/hworks");
            }catch (\Exception $exception){
                return redirect()->back()->withErrors(['msg'=>'FAIL_TO_SAVE']);
            }
        }
    }

    public function inputPage($hwid = ''){
        $pageFunctions = Configurations::$BMS_PAGE_FUNCTION_KEYS;
        $schoolGrades = schoolGrades::orderBy('scg_index','asc')->get();
        $subjects = BmsSubjects::orderBy('subject_index','asc')->get();

        $collections = new Collection();
        foreach ($pageFunctions as $pf){
            $collections->push((object)$pf);
        }

        $data = [];
        if ($hwid != ''){
            $data = BmsHworks::find($hwid);
        }

        return view('bms.hworks_input',['functions'=>$collections,'sgrades'=>$schoolGrades,'subjects'=>$subjects,'data'=>$data]);
    }

    public function getSubjects(Request $request){
        $sgId = $request->get('_sgId');
        $data = BmsSubjects::where('sg_id','=',$sgId)->orderBy('subject_index','asc')->get();

        $hworks = [];

        $nullWork = ["hwork_content"=>null,"hwork_dt"=>null,"hwork_book"=>null,"hwork_output_first"=>null,"hwork_output_second"=>null,"hwork_opt1"=>null,"hwork_opt2"=>null,"hwork_opt3"=>null,"hwork_opt4"=>null];

        foreach($data as $datum){
            $clsid = $datum->id;
            $hwork = BmsHworks::where('hwork_sgid','=',$sgId)
                ->where('hwork_class','=',$clsid)->first();
            if (isset($hwork)){
                $hworks[] = ["clsId"=>$clsid,"hdata"=>$hwork];
            }else{
                $hworks[] = ["clsId"=>$clsid,"hdata"=>$nullWork];
            }
        }

        return response()->json(['data'=>$data,'items'=>$hworks]);
    }

    public function storeClass(Request $request){
        $sgId = $request->get("saved_sg_id");
        $subjectId = $request->get("up_subject_id");

        $content = $request->get('up_content');
        $dt = $request->get('up_dt');
        $book = $request->get("up_book");
        $output_first = $request->get("up_output_first");
        $output_second = $request->get('up_output_second');
        $opt1 = $request->get("up_opt1");
        $opt2 = $request->get("up_opt2");
        $opt3 = $request->get("up_opt3");
        $opt4 = $request->get("up_opt4");

        $standard = ['hwork_class'=>$subjectId,'hwork_sgid'=>$sgId];
        $values = [
            'hwork_content'=>$content,'hwork_dt'=>$dt,'hwork_book'=>$book,'hwork_output_first'=>$output_first,'hwork_output_second'=>$output_second,
            'hwork_opt1'=>$opt1,'hwork_opt2'=>$opt2,'hwork_opt3'=>$opt3,'hwork_opt4'=>$opt4
            ];
        $save = BmsHworks::updateOrCreate($standard,$values);

        if (isset($save)){
            return response()->json(['result'=>'true']);
        }else{
            return response()->json(['result'=>'false']);
        }
    }
}
