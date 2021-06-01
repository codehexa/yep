<?php

namespace App\Http\Controllers;

use App\Models\LogTestAreas;
use Illuminate\Http\Request;

class LogTestAreasController extends Controller
{
    //
    public function addLog($mode,$target,$old,$new){
        $obj = new LogTestAreas();
        $obj->mode = $mode;
        $obj->target_id = $target;
        $obj->old_value = $old;
        $obj->new_value = $new;

        $obj->save();
    }
}
