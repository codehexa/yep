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
    public function index($sgId=''){

        $settings = Settings::where('set_code','=',Configurations::$SETTINGS_PAGE_LIMIT_CODE)->first();
        $limit = $settings->set_value;
        if ($sgId != ''){
            $data = BmsHworks::where('hwork_sgid','=',$sgId)->orderBy('id','asc')->paginate($limit);
        }else{
            $data = BmsHworks::orderBy('id','asc')->paginate($limit);
        }


        $schoolGrades = schoolGrades::orderBy("scg_index","asc")->get();
        $codes = Configurations::$BMS_PAGE_FUNCTION_KEYS;
        $collections = new Collection();
        foreach ($codes as $pf){
            $collections->push((object)$pf);
        }

        return view("bms.hworks",[
            "data"  => $data,"schoolGrades"=>$schoolGrades,'codes'=>$collections
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
}
