<?php

namespace App\Http\Controllers;

use App\Models\LogAcademies;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LogAcademiesController extends Controller
{
    //
    public function store($category,$mode,$target,$desc){
        $user = Auth::user();
        $data = new LogAcademies();
        $data->user_id = $user->id;
        $data->log_category = $category;
        $data->log_mode = $mode;
        $data->target_id = $target;
        $data->log_desc = $desc;

        try {
            $data->save();
            return true;
        }catch (\Exception $e){
            return false;
        }
    }
}
