<?php

namespace App\Http\Controllers;

use App\Models\LogScores;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LogScoresController extends Controller
{
    //
    public function addLog($mod,$target,$old,$new){
        $user = Auth::user();

        $logScore = new LogScores();
        $logScore->ss_mode = $mod;
        $logScore->ss_writer_id = $user->id;
        $logScore->ss_target_id = $target;
        $logScore->old_value = $old;
        $logScore->new_value = $new;

        $logScore->save();
    }
}
