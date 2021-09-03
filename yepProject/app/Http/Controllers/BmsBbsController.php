<?php

namespace App\Http\Controllers;

use App\Models\Academies;
use App\Models\BmsBbs;
use App\Models\BmsBbsComments;
use App\Models\Configurations;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BmsBbsController extends Controller
{
    //
    public function __construct(){
        return $this->middleware("auth");
    }

    public function getDataInAcademy($acid=''){
        if ($acid != ''){
            return BmsBbs::where('academy_id','=',$acid)
                ->where('bbs_type','=',Configurations::$BBS_TYPE_NORMAL)->orderBy('id','desc')->paginate(Configurations::$BBS_LIMIT);
        }else{
            return BmsBbs::where('bbs_type','=',Configurations::$BBS_TYPE_NORMAL)->orderBy('id','desc')->paginate(Configurations::$BBS_LIMIT);
        }

    }

    public function getListForAll(){
        return BmsBbs::where('bbs_type','=',Configurations::$BBS_TYPE_ALL)->orderBy('id','desc')->take(Configurations::$BBS_LIMIT);
    }

    public function index(){
        $data = BmsBbs::orderBy('bbs_type','asc')->orderBy('id','desc')->paginate(Configurations::$BBS_LIMIT);

        return view('bms.bbs_list',['data'=>$data]);
    }

    public function post(){
        $user = Auth::user();

        if ($user->power == Configurations::$USER_POWER_TEACHER){
            $academies = Academies::where('id','=',$user->academy_id)->orderBy('ac_name','asc')->get();
        }else{
            $academies = Academies::orderBy('ac_name','asc')->get();
        }

        return view('bms.bbs_post',['academies'=>$academies]);
    }

    public function add(Request $request){
        $user = Auth::user();
        $upAcademy = $request->get("up_academy");
        $upType = $request->get("up_type_all");
        $upTitle = $request->get("up_title");
        $upComment = $request->get('up_comment');

        if ($upType == "") {
            $upType = Configurations::$BBS_TYPE_NORMAL;
        }

        $bbs = new BmsBbs();
        $bbs->academy_id = $upAcademy;
        $bbs->us_id = $user->id;
        $bbs->us_name = $user->name;
        $bbs->bbs_title = $upTitle;
        $bbs->bbs_content = $upComment;
        $bbs->bbs_type = $upType;
        $bbs->bbs_hits = 0;
        $bbs->bbs_added_count = 0;

        try {
            $bbs->save();
            return redirect("/bms/bbs");
        }catch (\Exception $exception){
            return redirect()->back()->withErrors(['msg'=>'FAIL_TO_SAVE']);
        }
    }

    public function view($id){
        $data = BmsBbs::find($id);
        $data->increment('bbs_hits',1);

        $added = BmsBbsComments::where('bbs_id','=',$id)->orderBy('id','desc')->paginate(Configurations::$BBS_LIMIT);

        return view('bms.bbs_view',['data'=>$data,'added'=>$added]);
    }

    public function addMent(Request $request){
        $user = Auth::user();

        $comment = $request->get("_txt");
        $parent = $request->get("_parent");

        $add = new BmsBbsComments();
        $add->us_id = $user->id;
        $add->us_name = $user->name;
        $add->bbs_id = $parent;
        $add->bc_comment = $comment;

        try {
            $add->save();

            BmsBbs::find($parent)->increment('bbs_added_count',1);

            return response()->json(['result'=>'true','data'=>$add]);
        }catch (\Exception $exception){
            return response()->json(['result'=>'false']);
        }
    }

    public function edit($id=''){
        if ($id == ''){
            return redirect()->back()->withErrors(['msg'=>'FAIL_TO_MODIFY']);
        }

        $data = BmsBbs::find($id);

        $user = Auth::user();

        if ($user->power == Configurations::$USER_POWER_TEACHER && $data->us_id != $user->id){
            return redirect()->back()->withErrors(['msg'=>'FAIL_TO_MODIFY']);
        }

        if ($user->power == Configurations::$USER_POWER_TEACHER){
            $academies = Academies::where('id','=',$user->academy_id)->orderBy('ac_name','asc')->get();
        }else{
            $academies = Academies::orderBy('ac_name','asc')->get();
        }

        return view('bms.bbs_edit',['data'=>$data,'academies'=>$academies]);
    }

    public function modify(Request $request){
        $upId = $request->get("up_id");
        $upAcademy = $request->get("up_academy");
        $upTypeAll = $request->get("up_type_all");
        $upTitle = $request->get('up_title');
        $upComment = $request->get("up_comment");

        if ($upTypeAll == '') $upTypeAll = Configurations::$BBS_TYPE_NORMAL;

        $data = BmsBbs::find($upId);
        $data->academy_id = $upAcademy;
        $data->bbs_title = $upTitle;
        $data->bbs_content = $upComment;
        $data->bbs_type = $upTypeAll;

        try {
            $data->save();
            return redirect("/bms/bbsView/{$upId}");

        }catch (\Exception $exception){
            return redirect()->back()->withErrors(['msg'=>'FAIL_TO_MODIFY']);
        }
    }

    public function delMent(Request $request){
        $delId = $request->get("del_id");

        $d = BmsBbsComments::find($delId);

        try {
            $parentId = $d->bbs_id;
            $d->delete();

            $cnt = BmsBbsComments::where('bbs_id','=',$parentId)->count();
            $parentObj = BmsBbs::find($parentId);
            $parentObj->bbs_added_count = $cnt;
            $parentObj->save();

            return response()->json(["result"=>'true','data'=>$d]);
        }catch (\Exception $exception){
            return response()->json(['result'=>'false']);
        }
    }
}
