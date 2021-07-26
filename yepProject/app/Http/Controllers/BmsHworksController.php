<?php

namespace App\Http\Controllers;

use App\Models\BmsHworks;
use App\Models\Configurations;
use App\Models\Settings;
use Illuminate\Http\Request;

class BmsHworksController extends Controller
{
    //
    public function index(){
        $hworkTypes = Configurations::$BMS_HWORK_TYPES;

        $settings = Settings::where('set_code','=',Configurations::$SETTINGS_PAGE_LIMIT_CODE)->first();
        $limit = $settings->set_value;
        $data = BmsHworks::orderBy('id','asc')->paginate($limit);

        return view("bms.hworks",[
            "hwTypes"=>$hworkTypes,
            "data"  => $data
        ]);
    }
}
