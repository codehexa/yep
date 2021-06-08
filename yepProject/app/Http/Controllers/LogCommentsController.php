<?php

namespace App\Http\Controllers;

use App\Models\LogComments;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LogCommentsController extends Controller
{
    //
    public function __construct(){
        $this->middleware("auth");
    }

    public function addLog($mode,$target,$old,$new,$field){
        $userId = Auth::user()->id;

        $log = new LogComments();
        $log->cm_mode = $mode;
        $log->target_id = $target;
        $log->cm_old = $old;
        $log->cm_new = $new;
        $log->writer_id = $userId;
        $log->cm_field = $field;

        $log->save();
    }
}
