<?php

namespace App\Http\Controllers;

use App\Models\Comments;
use App\Models\schoolGrades;
use App\Models\TestAreas;
use Illuminate\Http\Request;

class CommentsController extends Controller
{
    //
    public function index($grade='',$taId=''){
        $sGrades = schoolGrades::orderBy('scg_index','asc')->get();
        $data = [];

        if ($grade != ''){
            $subjects = TestAreas::where('ta_school_grade','=',$grade)
                ->orderBy('ta_depth','asc')
                ->orderBy('parent_id','asc')
                ->get();
        }else{
            $subjects = TestAreas::orderBy('ta_depth','asc')
                ->orderBy('parent_id','asc')
                ->get();
        }

        if ($grade != '' && $taId != ''){
            $data = Comments::where('scg_id','=',$grade)
                ->where('ta_id','=',$taId)
                ->orderBy('min_score','asc')
                ->get();
        }
        return view("comments.index",[
            "grades"=>$sGrades,'rGrade'=>$grade,'rTaId'=>$taId,
            'subjects'=>$subjects,
            'data'  => $data
        ]);
    }
}
