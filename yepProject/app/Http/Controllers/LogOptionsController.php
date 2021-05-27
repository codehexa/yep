<?php

namespace App\Http\Controllers;

use App\Models\Configurations;
use App\Models\LogOptions;
use App\Models\Settings;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LogOptionsController extends Controller
{
    //
    public function addLog($code,$old,$new,$desc){
        $user = Auth::user();

        $logOption = new LogOptions();
        $logOption->user_id = $user->id;
        $logOption->opt_code = $code;
        $logOption->opt_old_value = $old;
        $logOption->opt_new_value = $new;
        $logOption->opt_log_desc = $desc;

        $logOption->save();
    }

    public function optionLogList(){
        $pageListObj = Settings::where('set_code','=',Configurations::$SETTINGS_PAGE_LIMIT_CODE)->first();
        $limit = $pageListObj->set_value;

        $data = LogOptions::orderBy('id','desc')->paginate($limit);

        return view('logs.log_options',["data"=>$data]);
    }
}
