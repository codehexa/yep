<?php

namespace App\Http\Controllers;

use App\Models\BmsPageSettings;
use App\Models\Configurations;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class BmsPageSettingsController extends Controller
{
    //
    public function __construct(){
        return $this->middleware('auth');
    }


    public function index(){
        $data = BmsPageSettings::orderBy('field_index','asc')->get();
        $tagsArray = Configurations::$BMS_PAGE_FUNCTION_KEYS;
        $tagsCollection = new Collection();
        foreach($tagsArray as $tags){
            $tagsCollection->push((object)$tags);
        }


        return view('bms.page_setting',['data'=>$data,'tags'=>$tagsCollection]);
    }

    public function addSetting(Request $request){
        $name = $request->get("up_field_title");
        $txt = $request->get("up_field_function");
        $indexCnt = $this->getCount();

        $root = new BmsPageSettings();
        $root->field_name = $name;
        $root->field_index = $indexCnt;
        $root->field_function = $txt;

        try {
            $root->save();
            return redirect("/bms/pageSetting");
        }catch (\Exception $exception){
            return redirect()->back()->withErrors(['msg'=>'FAIL_TO_SAVE']);
        }

    }

    public function getCount(){
        $cnt = BmsPageSettings::count();

        return $cnt;
    }
}
