<?php

namespace App\Http\Controllers;

use App\Models\BmsPageSettings;
use App\Models\Configurations;
use App\Models\schoolGrades;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class BmsPageSettingsController extends Controller
{
    //
    public function __construct(){
        return $this->middleware('auth');
    }


    public function index($element_id=''){
        if (isset($element_id) && $element_id != ''){
            $data = BmsPageSettings::find($element_id)->get();
        }else{
            $data = BmsPageSettings::orderBy('field_index','asc')->get();
        }


        $tagsArray = Configurations::$BMS_PAGE_FUNCTION_KEYS;
        $tagsCollection = new Collection();
        foreach($tagsArray as $tags){
            $tagsCollection->push((object)$tags);
        }

        $fieldsCollection = new Collection();
        $nClassName = new \stdClass();
        $nClassName->code = Configurations::$BMS_SMS_TAG_CLASSNAME; $nClassName->title = Configurations::$BMS_SMS_TAG_CLASSNAME_TITLE;
        $nGreeting = new \stdClass();
        $nGreeting->code = Configurations::$BMS_SMS_TAG_GREETING; $nGreeting->title = Configurations::$BMS_SMS_TAG_GREETING_TITLE;
        $nBookWork = new \stdClass();
        $nBookWork->code = Configurations::$BMS_SMS_TAG_BOOK_WORK; $nBookWork->title = Configurations::$BMS_SMS_TAG_BOOK_WORK_TITLE;
        $nOutput = new \stdClass();
        $nOutput->code = Configurations::$BMS_SMS_TAG_OUTPUT_WORK; $nOutput->title = Configurations::$BMS_SMS_TAG_OUTPUT_WORK_TITLE;
        $nNotice = new \stdClass();
        $nNotice->code = Configurations::$BMS_SMS_TAG_NOTICE; $nNotice->title = Configurations::$BMS_SMS_TAG_NOTICE_TITLE;
        $nAcademy = new \stdClass();
        $nAcademy->code = Configurations::$BMS_SMS_TAG_ACADEMY_INFO; $nAcademy->title = Configurations::$BMS_SMS_TAG_ACADEMY_INFO_TITLE;

        $fieldsCollection->push($nGreeting);
        $fieldsCollection->push($nClassName);
        $fieldsCollection->push($nBookWork);
        $fieldsCollection->push($nOutput);
        $fieldsCollection->push($nNotice);
        $fieldsCollection->push($nAcademy);

        return view('bms.page_setting',['data'=>$data,'tags'=>$tagsCollection,'fieldsTitles'=>$fieldsCollection]);
    }

    public function addSetting(Request $request){
        $name = $request->get("up_field_title");
        $txt = $request->get("up_field_function");
        $tag = $request->get("up_field_tags");

        $root = new BmsPageSettings();
        $root->field_name = $name;
        $root->field_tag = $tag;
        $root->field_function = $txt;

        $cnt = $this->getCount();
        $root->field_index = $cnt;

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

    public function storeSetting(Request $request){
        $upId = $request->get("up_pg_id");
        $upFieldTitle = $request->get("up_field_title");
        $upFieldTag = $request->get("up_field_tags");
        $upFieldFunction = $request->get("up_field_function");

        $d = BmsPageSettings::find($upId);
        $d->field_name = $upFieldTitle;
        $d->field_tag = $upFieldTag;
        $d->field_function = $upFieldFunction;

        try {
            $d->save();
            return redirect("/bms/pageSetting");
        }catch (\Exception $exception){
            return redirect()->back()->withErrors(['msg'=>'FAIL_TO_MODIFY']);
        }
    }

    public function saveSort(Request $request){
        $ids = $request->get("sortIds");

        $arr = explode(",",$ids);

        for ($i=0; $i < sizeof($arr); $i++){
            $d = BmsPageSettings::find($arr[$i]);
            $d->field_index = $i;
            $d->save();
        }

        return response()->json(["result"=>"true"]);
    }

    public function deleteDo(Request $request){
        $delId = $request->get("del_id");

        $d = BmsPageSettings::find($delId);
        $fIndex = $d->field_index;
        try {
            $d->delete();
            BmsPageSettings::where('field_index','>=',$fIndex)
            ->update(['field_index'=>DB::raw('field_index -1')]);

            return redirect("/bms/pageSetting");
        }catch (\Exception $exception){
            return redirect()->back()->withErrors(['msg'=>'FAIL_TO_DELETE']);
        }
    }
}
