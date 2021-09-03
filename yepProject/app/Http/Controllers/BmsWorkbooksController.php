<?php

namespace App\Http\Controllers;

use App\Models\BmsWorkbooks;
use Illuminate\Http\Request;

class BmsWorkbooksController extends Controller
{
    //
    public function addWorkbook(Request $request){
        $title = $request->get("up_book_title");
        $text = $request->get("up_book_text");

        $check = BmsWorkbooks::where('bw_title','=',$title)->count();

        if ($check > 0){
            return response()->json(['result'=>'false']);
        }else{
            $d = new BmsWorkbooks();
            $cnt = BmsWorkbooks::count();

            $d->bw_title = $title;
            $d->bw_text = $text;
            $d->bw_index = $cnt;

            try {
                $d->save();
                return response()->json(['result'=>'true','data'=>$d]);
            }catch (\Exception $exception){
                return response()->json(['result'=>'false']);
            }
        }
    }

    public function saveSortWorkbooks(Request $request){
        $ids = $request->get("_dels");

        $arr = explode(",",$ids);

        for ($i=0; $i < sizeof($arr); $i++){
            $d = BmsWorkbooks::find($arr[$i]);
            $d->bw_index = $i;
            $d->save();
        }

        return response()->json(['result'=>'true']);
    }

    public function storeWorkbook(Request $request){
        $id = $request->get("_upId");
        $title = $request->get("_upTitle");
        $txt = $request->get("_upText");

        $d = BmsWorkbooks::find($id);
        $d->bw_title = $title;
        $d->bw_text = $txt;

        try {
            $d->save();

            return response()->json(['result'=>'true']);
        }catch (\Exception $exception){
            return response()->json(['result'=>'false']);
        }
    }

    public function deleteWorkbook(Request $request){
        $ids = $request->get("_ids");

        $idsArray = explode(",",$ids);
        try {
            BmsWorkbooks::whereIn("id",$idsArray)->delete();
            return response()->json(['result'=>'true']);
        }catch (\Exception $exception){
            return response()->json(['result'=>'false']);
        }
    }
}
