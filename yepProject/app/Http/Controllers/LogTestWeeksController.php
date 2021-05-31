<?php

namespace App\Http\Controllers;

use App\Models\LogTestWeeks;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LogTestWeeksController extends Controller
{
    //
    public function addLog($target_id,$log_mode,$log_old,$log_new){
        $user = Auth::user();

        $obj = new LogTestWeeks();
        $obj->user_id = $user->id;
        $obj->target_id = $target_id;
        $obj->log_mode = $log_mode;
        $obj->log_old = $log_old;
        $obj->log_new = $log_new;

        $obj->save();
    }
}
