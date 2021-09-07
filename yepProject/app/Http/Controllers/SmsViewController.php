<?php

namespace App\Http\Controllers;

use App\Models\Comments;
use App\Models\Configurations;
use App\Models\SmsPageSettings;
use App\Models\SmsPapers;
use App\Models\SmsScores;
use App\Models\SmsSendResults;
use App\Models\Students;
use App\Models\TestForms;
use App\Models\TestFormsItems;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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

        $opinionsAll = [];
        $opinionN = 0;

        if (is_null($student)){
            return redirect()->back()->withErrors(['msg'=>'NO_MATCH_STUDENT']);
        }else{
            $student_id = $student->id;
            $smsSettings = SmsPageSettings::first();
            $smsPaperFirst = $smsPapers->first();

            $smsSendResult = SmsSendResults::where('sms_paper_code','=',$upCode)
                ->where('student_id','=',$student_id)->first();
            $smsSendResult->ssr_view = Configurations::$SMS_SEND_VIEW_Y;
            $smsSendResult->save();

            $dataSet = [];  // dataSet 에는 타이틀과 이전 데이터 현재 데이터를 포함한 데이터를 규격한다.

            $teacherSays = [];
            $wordians = [];

            $jsData = [];

            foreach ($smsPapers as $sPaper){
                $sgId = $sPaper->sg_id;
                $tfId = $sPaper->tf_id;
                $year = $sPaper->year;
                $week = $sPaper->week;

                $nowJsData = [];

                $testFormData = TestForms::find($tfId);
                $testFormChildData = TestFormsItems::where('tf_id','=',$tfId)->orderBy('sj_index','asc')->get();
                $studentNowScore = SmsScores::where('tf_id','=',$tfId)->where('st_id','=',$student_id)
                    ->where('sg_id','=',$sgId)
                    ->where('year','=',$year)->where('week','=',$week)
                    ->first();

                $teacherSays[] = $studentNowScore->opinion;
                if (!is_null($studentNowScore->wordian)){
                    $wordians[] = $studentNowScore->wordian;
                }


                $studentPreScore = null;
                if ($week > 1){
                    $studentPreScore = SmsScores::where('tf_id','=',$tfId)->where('st_id','=',$student_id)
                        ->where('sg_id','=',$sgId)
                        ->where('year','=',$year)->where('week','=',$week -1)
                        ->first();
                }
                $subjectN = 0;
                $hasPreScore = "N";
                if (!is_null($studentPreScore)) $hasPreScore = "Y";

                //$cItems=[];

                //$innerData = [];
                //$subjectTitles = [];

                // test items setting
                //dd($testFormChildData);
                $parent_subject_title = "";
                $saved_parent_id = -1;
                $stack = 1;
                $score_N = 0;


                for ($i=0; $i < sizeof($testFormChildData); $i++){
                    $cItem = $testFormChildData[$i];
                    $scoreFieldName = "score_".$score_N;
                    if (is_null($studentPreScore)) {
                        $preScore = "0";
                    }else{
                        $preScore = $preScore = $studentPreScore->$scoreFieldName;
                    }
                    if (is_null($studentNowScore)){
                        $nowScore = "0";
                    }else{
                        $nowScore = $studentNowScore->$scoreFieldName;
                    }

                    $parent_title = "";
                    if ($cItem->sj_depth == "1"){
                        $parent_title = "";
                    }


                    if ($cItem->sj_depth == "0" && $cItem->sj_has_child == "N" && $cItem->sj_type != "T"){   // 대표 혹은 셀프
                        $nowJsData[$score_N]["labels"] = $cItem->sj_title;
                        $nowJsData[$score_N]["scores"] = "{$preScore},{$nowScore}";
                        $nowJsData[$score_N]["id"]   = $cItem->id;
                        $nowJsData[$score_N]["stack"]    = $stack;
                        $stack++;
                        $score_N++;
                    }else if ($cItem->sj_depth != "0" && $cItem->sj_has_child == "N" && $cItem->sj_type != "T"){    // inner
                        for ($k = 0; $k < sizeof($testFormChildData); $k++){
                            if ($testFormChildData[$k]["id"] == $cItem->sj_parent_id){
                                $parent_title = $testFormChildData[$k]->sj_title;
                                break;
                            }
                        }
                        $nowJsData[$score_N]["labels"]   = $parent_title." > ".$cItem->sj_title;
                        $nowJsData[$score_N]["scores"]   = "{$preScore},{$nowScore}";
                        $nowJsData[$score_N]["id"]   = $cItem->id;
                        $nowJsData[$score_N]["stack"]    = $stack;
                        $score_N++;
                    }
                }

                $jsData[] = $nowJsData;

                $opinionN++;
                $formSet = [];
                $formSet['exam']    = $testFormData->exam;
                $formSet['testTitle'] = $testFormData->form_title;

                $dataSet[] = $formSet;
            }

            return view('parents.detail',[
                'papers'=>$smsPapers,'student'=>$student,
                'settings'=>$smsSettings, 'smsPaper'=>$smsPaperFirst,
                'jsData'=>$jsData,
                'dataSet'=>$dataSet,
                'scoreAnalysis' => $opinionsAll,
                'teacherSays'=>$teacherSays,
                "wordians"=>$wordians
            ]);
        }
    }

    // get opinion by score
    public function getOpinion($sjId,$score){
        $data = Comments::where('sj_id','=',$sjId)
            ->where('min_score','<=',$score)
            ->where('max_score','>=',$score)
            ->first();

        if (!is_null($data)){
            if (is_null($data->opinion)) {
                return "선생님 입력해 주세요.";
            }
            return $data->opinion;
        }else{
            return "DB에 없네요.";
        }
    }

    // test form get
    public function getTestForm($tfId){

    }


}
