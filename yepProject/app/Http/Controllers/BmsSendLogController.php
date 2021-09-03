<?php

namespace App\Http\Controllers;

use App\Models\BmsSendLogs;
use App\Models\Configurations;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BmsSendLogController extends Controller
{
    //
    public function __construct(){
        return $this->middleware("auth");
    }

    public function index($year='',$month=''){
        if ($year == '' || $month == ''){
            $year = date("Y");
            $month = date("m");
        }
        if ($month < 10) $month = "0{$month}";
        $toMonth = $year."-".$month;

        $data = BmsSendLogs::where(DB::raw("DATE_FORMAT(updated_at,'%Y-%m')"),$toMonth)->orderBy('id','desc')->paginate(Configurations::$BBS_LIMIT);
        return view('bms.send_log',['data'=>$data,'nYear'=>$year,'nMonth'=>$month]);
    }
}
