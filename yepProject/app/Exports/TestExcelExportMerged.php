<?php

namespace App\Exports;

use App\Http\Controllers\TestFormsController;
use App\Models\SmsPapers;
use App\Models\SmsScores;
use App\Models\TestFormsItems;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;
use Ramsey\Collection\Collection;


class TestExcelExportMerged implements FromView
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function __construct($sper){
        $this->pp = $sper;
    }
    public function view():View
    {
        $ppId = $this->pp;
        $ppArray = explode("_",$ppId);
        $papers = SmsPapers::select('tf_id')->whereIn('id',$ppArray)->distinct()->groupBy('tf_id')->get();

        $tfId = 0;
        $classIds = [];
        $year = "";
        $week = "";

        foreach ($papers as $pp){
            $classIds[] = $pp->cl_id;
            $tfId = $pp->tf_id;
            $year = $pp->year;
            $week = $pp->week;
        }

        dd($classIds);
        $formCtrl = new TestFormsController();

        $testFormItems = TestFormsItems::where('tf_id','=',$tfId)->where('sj_parent_id','=',0)->orderBy('sj_index','asc')->get();
        $tItems = [];
        $head_col_size = 0;
        foreach($testFormItems as $formItem){
            if ($formItem->sj_has_child == "Y"){
                $hasDouble = "Y";
                $children = $formCtrl->getTestFormItemChildren($formItem->id);
                $formItem->setAttribute("child_size",sizeof($children));
                $tItems[] = $formItem;
                foreach($children as $child){
                    $tItems[] = $child;
                    $head_col_size++;
                }
            }else{
                $tItems[] = $formItem;
                $head_col_size++;
            }
        }

        $classesTxt = implode(",",$classIds);
        $data = SmsScores::where('tf_id','=',$tfId)
            ->whereIn('cl_id',$classesTxt)
            ->get();

        $fields = [];
        for ($i=0; $i < $head_col_size; $i++){
            $fields[] = "smsscores.score_".$i;
        }
        $field_names = implode("','",$fields);

        $data = DB::table('smsscores')
            ->join('students','smsscores.st_id','=','students.id')
            ->join('classes','smsscores.cl_id','=','classes.id')
            ->select("smsscores.*","students.student_name","students.school_name","students.school_grade","students.teacher_name","classes.class_name")
            ->where('smsscores.tf_id','=',$tfId)
            ->whereIn('smsscores.cl_id',$classesTxt)
            ->where('smsscores.year','=',$year)
            ->where('smsscores.week','=',$week)
            ->where('students.is_live','=','Y')
            ->get();

        $responses = collect();
        foreach ($data as $datum){
            $item = [
                "student_name"  => $datum->student_name,
                "school_name"   => $datum->school_name,
                "school_grade"  => $datum->school_grade,
                "teacher_name"  => $datum->teacher_name,
                "class_name"    => $datum->class_name,
                "teacher_say"   => $datum->opinion,
                "wordian"   => $datum->wordian
            ];
            for ($i=0; $i < $head_col_size; $i++){
                $fieldName = "score_".$i;
                $item[$fieldName] = $datum->$fieldName;
            }
            $responses->push((object)$item);
        }

        return view('exports.papers',['items'=>$tItems,
            'no'=>$week,
            'head_colspan'=>$head_col_size,
            'data'=>$responses
        ]);
    }
}
