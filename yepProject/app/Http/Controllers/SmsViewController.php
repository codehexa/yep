<?php

namespace App\Http\Controllers;

use App\Models\SmsPageSettings;
use App\Models\SmsPapers;
use App\Models\SmsScores;
use App\Models\Students;
use App\Models\TestForms;
use App\Models\TestFormsItems;
use Illuminate\Http\Request;

class SmsViewController extends Controller
{
    //
    public function smsView($code=''){
        /*
         * 맨 앞자리 3자리 랜덤코드
         * 중간 sms_papers.sp_code.
         * 맨 뒤는 4자리 랜덤코드
         */
        $last_code = "";
        $result = "false";
        if (strlen($code) > 0 && ctype_xdigit($code)){
            $decode = hex2bin($code);
            $first_code = substr($decode,3,strlen($decode) - 3);
            $last_code = substr($first_code,0,strlen($first_code) -4);
            $result = "true";
        }

        return view('parents.view',['code'=>$last_code,'result'=>$result]);
    }

    public function viewDetail(Request $request){
        $upCode = $request->get("up_code");
        $upTel = $request->get("up_parent_tel");

        $smsPapers = SmsPapers::where('sp_code','=',$upCode)->get();
        if (is_null($smsPapers)){
            return redirect()->back()->withErrors(['msg'=>'NO_MATCH_STUDENT']);
        }

        foreach($smsPapers as $smsPaper){
            $clId = $smsPaper->cl_id;
        }

        $student = Students::where('class_id','=',$clId)->where('parent_hp','=',$upTel)->first();

        if (is_null($student)){
            return redirect()->back()->withErrors(['msg'=>'NO_MATCH_STUDENT']);
        }else{
            $student_id = $student->id;
            $smsSettings = SmsPageSettings::first();
            $smsPaperFirst = $smsPapers->first();

            $dataSet = [];  // dataSet 에는 타이틀과 이전 데이터 현재 데이터를 포함한 데이터를 규격한다.
            $testSets = []; // 시험 정보 배열
            $subjectsSets = [];
            $testFormData = [];
            $testFormChildData = [];
            $studentScores = [];
            foreach ($smsPapers as $sPaper){
                $clId = $sPaper->cl_id;
                $sgId = $sPaper->sg_id;
                $tfId = $sPaper->tf_id;
                $year = $sPaper->year;
                $week = $sPaper->week;

                $testFormData = TestForms::find($tfId);
                $testFormChildData = TestFormsItems::where('tf_id','=',$tfId)->orderBy('sj_index','asc')->get();
                $studentNowScore = SmsScores::where('tf_id','=',$tfId)->where('st_id','=',$student_id)
                    ->where('sg_id','=',$sgId)
                    ->where('year','=',$year)->where('week','=',$week)
                    ->first();
                if ($week > 1){
                    $studentPreScore = SmsScores::where('tf_id','=',$tfId)->where('st_id','=',$student_id)
                        ->where('sg_id','=',$sgId)
                        ->where('year','=',$year)->where('week','=',$week -1)
                        ->first();
                }else{
                    $studentPreScore = [];
                }
                $testSets[] = $testFormData;
                $subjectsSets[] = $testFormChildData;
                $studentScores[] = ["preScore"=>$studentPreScore,"nowScore"=>$studentNowScore];
            }

            //dd($studentScores);

            return view('parents.detail',[
                'papers'=>$smsPapers,'student'=>$student,
                'settings'=>$smsSettings, 'smsPaper'=>$smsPaperFirst,
                'testForms'=>$testSets,
                'subjects'=>$subjectsSets,
                'scores'=>$studentScores
            ]);
        }
    }

    // test form get
    public function getTestForm($tfId){

    }


}
