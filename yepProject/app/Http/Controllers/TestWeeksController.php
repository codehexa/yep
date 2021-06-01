<?php

namespace App\Http\Controllers;

use App\Models\Configurations;
use App\Models\Hakgi;
use App\Models\LogTestWeeks;
use App\Models\schoolGrades;
use App\Models\TestWeeks;
use Illuminate\Http\Request;
use PhpParser\Node\Expr\Cast\Object_;

class TestWeeksController extends Controller
{
    //
    public function list($year='',$gradeVal='',$hakgi=''){
        $whereArray = [];
        if ($year != ''){
            $whereArray[] = ["year",'=',$year];
        }
        if ($hakgi != ''){
            $whereArray[] = ["hakgi","=",$hakgi];
        }

        if ($gradeVal != ''){
            $whereArray[] = ["school_grade",'=',$gradeVal];
        }

        $data = TestWeeks::where($whereArray)->orderBy('weeks','asc')->get();

        $sgrades = schoolGrades::orderBy("scg_index","asc")->get();

        $tmpHakgi = [];
        if ($gradeVal != ""){
            $tmpHakgi = Hakgi::where('school_grade','=',$gradeVal)
                ->where('show','=','Y')
                ->where('year','=',$year)
                ->orderBy('hakgi_name','asc')->get();
        }


        return view('testweek.list',["data"=>$data,"sgrades"=>$sgrades,"tmpHakgies"=>$tmpHakgi,"ryear"=>$year,"rgrade"=>$gradeVal,"rhakgi"=>$hakgi]);
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
        $grade = $request->get("info_grade");
        $hakgi = $request->get("info_hakgi");

        $check = TestWeeks::where('year','=',$year)
            ->where('weeks','=',$weeks)
            ->where('hakgi','=',$hakgi)
            ->where('school_grade','=',$grade)
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
            $tw->school_grade = $grade;

            try {
                $tw->save();

                $targetId = $tw->id;
                $logMode = "add";
                $oldValue = "";
                $newValue = $context;

                $twCtrl = new LogTestWeeksController();
                $twCtrl->addLog($targetId,$logMode,$oldValue,$newValue);

                return redirect()->route("testweeks");
            }catch (\Exception $e){
                return redirect()->back()->withErrors(['msg'=>'FAIL_TO_SAVE']);
            }
        }
    }

    public function testWeekJson(Request $request){
        $cid = $request->get("cid");

        $data = TestWeeks::find($cid);

        $nowSchoolGrade = $data->school_grade;

        $nowHakgiList = Hakgi::where('school_grade','=',$nowSchoolGrade)
            ->where('show','=','Y')->orderBy('hakgi_name')->get();

        return response()->json(['result'=>'true','data'=>$data,'hakgiData'=>$nowHakgiList]);
    }

    public function storeTestWeek(Request $request){
        $infoId = $request->get("info_id");
        $year = $request->get("info_year");
        $week = $request->get("info_week");
        $grade = $request->get("info_grade");
        $hakgi = $request->get("info_hakgi");
        $show = $request->get("info_show");
        $context = $request->get("info_context");

        $oldObj = TestWeeks::find($infoId);

        $logMode = "modify";
        $logTarget = $infoId;

        if ($oldObj->year != $year){
            $logOld = $oldObj->year;
            $logNew = $year;
            $this->addTestWeekLog($logTarget,$logMode,$logOld,$logNew);

            $oldObj->year = $year;
        }
        if ($oldObj->weeks != $week){
            $logOld = $oldObj->weeks;
            $logNew = $week;
            $this->addTestWeekLog($logTarget,$logMode,$logOld,$logNew);

            $oldObj->weeks = $week;
        }

        if ($oldObj->context != $context){
            $logOld = $oldObj->context;
            $logNew = $context;
            $this->addTestWeekLog($logTarget,$logMode,$logOld,$logNew);

            $oldObj->context = $context;
        }

        if ($oldObj->show != $show){
            $logOld = $oldObj->show;
            $logNew = $show;
            $this->addTestWeekLog($logTarget,$logMode,$logOld,$logNew);

            $oldObj->show = $show;
        }

        if ($oldObj->hakgi != $hakgi){
            $logOld = $oldObj->hakgi;
            $logNew = $hakgi;
            $this->addTestWeekLog($logTarget,$logMode,$logOld,$logNew);

            $oldObj->hakgi = $hakgi;
        }

        if ($oldObj->school_grade != $grade){
            $logOld = $oldObj->school_grade;
            $logNew = $grade;
            $this->addTestWeekLog($logTarget,$logMode,$logOld,$logNew);

            $oldObj->school_grade = $grade;
        }

        try {
            $oldObj->save();

            return redirect()->route("testweeks");
        }catch (\Exception $e){
            return redirect()->back()->withErrors(['msg'=>'FAIL_TO_MODIFY']);
        }
    }

    public function addTestWeekLog($target,$mode,$old,$new){
        $logCtrl = new LogTestWeeksController();
        $logCtrl->addLog($target,$mode,$old,$new);
    }

    public function delTestWeek(Request $request){
        $delId = $request->get("del_id");

        $data = TestWeeks::find($delId);

        try {
            $data->delete();

            $target = $delId;
            $mode = "delete";
            $old = $data->context;
            $new = "";
            $this->addTestWeekLog($target,$mode,$old,$new);

            return redirect()->route("testweeks");

        } catch (\Exception $e){
            return redirect()->back()->withErrors(['msg'=>'FAIL_TO_DELETE']);
        }
    }
}
