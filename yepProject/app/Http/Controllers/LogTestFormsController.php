<?php

namespace App\Http\Controllers;

use App\Models\LogTestForms;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LogTestFormsController extends Controller
{
    //
    public function __construct(){
        $this->middleware('auth');
    }

    public function addLog($mode,$target,$old,$new,$field){
        $user = Auth::user();

        $data = new LogTestForms();
        $data->lt_mode = $mode;
        $data->lt_id = $target;
        $data->lt_old = $old;
        $data->lt_new = $new;
        $data->writer_id = $user->id;
        $data->lt_field = $field;

        $data->save();
    }
}
