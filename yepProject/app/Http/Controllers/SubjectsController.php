<?php

namespace App\Http\Controllers;

use App\Models\schoolGrades;
use Illuminate\Http\Request;

class SubjectsController extends Controller
{
    //
    public function index(){
        $grades = schoolGrades::orderBy('scg_index','asc')->get();

        return view("subjects.index",["grades"=>$grades]);
    }
}
