<?php

namespace App\Http\Controllers;

use App\Models\BmsPageSettings;
use App\Models\Configurations;
use App\Models\schoolGrades;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class BmsPageSettingsController extends Controller
{
    //
    public function __construct(){
        return $this->middleware('auth');
    }


    public function index($sgid = ''){
        $data = [];
        if ($sgid != ""){
            $data = BmsPageSettings::where('sg_id','=',$sgid)->orderBy('field_index','asc')->get();
        }

        $tagsArray = Configurations::$BMS_PAGE_FUNCTION_KEYS;
        $tagsCollection = new Collection();
        foreach($tagsArray as $tags){
            $tagsCollection->push((object)$tags);
        }

        $sgrades = schoolGrades::orderBy('scg_index','asc')->get();
        return view('bms.page_setting',['data'=>$data,'tags'=>$tagsCollection,'sgrades'=>$sgrades,'sgrade_val'=>$sgid]);
    }

    public function addSetting(Request $request){
        $name = $request->get("up_field_title");
        $txt = $request->get("up_field_function");
        $sgId = $request->get("up_sg_id");
        $indexCnt = $this->getCount($sgId);

        $root = new BmsPageSettings();
        $root->field_name = $name;
        $root->field_index = $indexCnt;
        $root->field_function = $txt;
        $root->sg_id = $sgId;

        try {
            $root->save();
            return redirect("/bms/pageSetting/".$sgId);
        }catch (\Exception $exception){
            return redirect()->back()->withErrors(['msg'=>'FAIL_TO_SAVE']);
        }

    }

    public function getCount($sgId){
        $cnt = BmsPageSettings::where('sg_id','=',$sgId)->count();

        return $cnt;
    }
}
