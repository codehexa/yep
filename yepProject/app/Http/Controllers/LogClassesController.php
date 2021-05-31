<?php

namespace App\Http\Controllers;

use App\Models\LogClasses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LogClassesController extends Controller
{
    //
    public function __construct(){
        $this->middleware('auth');
    }

    public function addLog($category,$mode,$target,$desc){
        $user = Auth::user();
        $userId = $user->id;

        $logClass = new LogClasses();
        $logClass->user_id = $userId;

        $logClass->log_category = $category;
        $logClass->log_mode = $mode;
        $logClass->target_id = $target;
        $logClass->log_desc = $desc;

        $logClass->save();
    }
}
