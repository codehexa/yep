<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BmsEditorController extends Controller
{
    //
    public function index(){
        return view('bms.editor');
    }
}
