<?php

namespace App\Http\Controllers;

use App\Models\Configurations;
use App\Models\LogAcademies;
use App\Models\LogUsers;
use App\Models\Settings;
use Illuminate\Http\Request;

class LogsViewController extends Controller
{
    //
    public function all(){
        return view('pages.logs_all');
    }

    public function academy(){
        $limitObj = Settings::where('set_code','=',Configurations::$SETTINGS_PAGE_LIMIT_CODE)->first();
        $limit = $limitObj->set_value;
        $data = LogAcademies::orderBy('id','desc')->paginate($limit);
        return view('logs.log_academy',["data"=>$data]);
    }

    public function users(){
        $limitObj = Settings::where('set_code','=',Configurations::$SETTINGS_PAGE_LIMIT_CODE)->first();
        $limit = $limitObj->set_value;
        $data = LogUsers::orderBy('id','desc')->paginate($limit);
        return view('logs.log_users',["data"=>$data]);
    }
}
