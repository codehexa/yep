<?php

namespace App\Http\Controllers;

use App\Models\LogUsers;
use Illuminate\Http\Request;

class LogUsersController extends Controller
{
    //
    public function addLog($uid,$target,$fname,$old,$new){
        $logUser = new LogUsers();

        $logUser->user_id = $uid;
        $logUser->target_id = $target;
        $logUser->field_name = $fname;
        $logUser->old_value = $old;
        $logUser->new_value = $new;

        try {
            $logUser->save();
            return true;
        }catch (\Exception $e){
            return false;
        }
    }
}
