<?php

namespace App\Http\Controllers;

use App\Models\LogStudents;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LogStudentsController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware("auth");
    }

    public function addLog($mode,$target,$field,$old,$new){
        $user = Auth::user();
        $log = new LogStudents();
        $log->ls_mode = $mode;
        $log->ls_writer_id = $user->id;
        $log->ls_target_id = $target;
        $log->ls_field = $field;
        $log->old_value = $old;
        $log->new_value = $new;

        try {
            $log->save();
        }catch (\Exception $e){

        }
    }
}
