<?php

namespace App\Http\Controllers;

use App\Models\LnCurriSubject;
use App\Models\schoolGrades;
use Illuminate\Http\Request;

class LnCurriSubjectController extends Controller
{
    //
    public function index($rGrade=''){
        $grades = schoolGrades::orderBy('scg_index','asc')->get();

        $data = LnCurriSubject::select("subjects.*","curriculums.*")
            ->leftJoin('subjects','subjects.id','=','ln_curri_subject.subject_id')
            ->leftJoin('curriculums','curriculum.id','=','ln_curri_subject.curri_id')
            ->orderBy('curriculums.curri_name');

        return view("kwamoks.index",["grades"=>$grades,"data"=>$data,"rGrade"=>$rGrade]);
    }
}
