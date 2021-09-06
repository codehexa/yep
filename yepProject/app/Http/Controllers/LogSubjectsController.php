<?php

namespace App\Http\Controllers;

use App\Models\LogSubjects;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LogSubjectsController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware("auth");
    }

    public function addLog($mod,$target,$old,$new,$field){
        $user = Auth::user();

        $logSubject = new LogSubjects();
        $logSubject->sj_mode = $mod;
        $logSubject->target_id = $target;
        $logSubject->sj_old = $old;
        $logSubject->sj_new = $new;
        $logSubject->writer_id = $user->id;
        $logSubject->log_field = $field;

        $logSubject->save();
    }
}
