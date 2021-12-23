<?php

namespace App\Http\Controllers;

use App\Models\LogSmsPapers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LogSmsPapersController extends Controller
{
    //
    public function addLog($mod,$ppid,$old,$new,$field){
        $user = Auth::user();
        $paper = new LogSmsPapers();
        $paper->lsp_mode = $mod;
        $paper->lsp_writer_id = $user->id;
        $paper->lsp_sms_paper_id = $ppid;
        $paper->old_value = $old;
        $paper->new_value = $new;
        $paper->lsp_field = $field;

        $paper->save();
    }
}
