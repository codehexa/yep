<?php

namespace App\Http\Controllers;

use App\Models\Configurations;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;

class ManualsController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function showDown($n){
        $user = Auth::user();

        switch ($n){
            case "0":
                if ($user->power != Configurations::$USER_POWER_ADMIN){
                    return redirect()->back()->withErrors(['msg'=>'NO_USER_POWER_MATCH']);
                }else{
                    $filename = public_path(Configurations::$MANUAL_ADMIN);
                    return response()->download($filename);
                }

            case "1":
                if ($user->power != Configurations::$USER_POWER_MANAGER){
                    return redirect()->back()->withErrors(['msg'=>'NO_USER_POWER_MATCH']);
                }else{
                    $filename = public_path(Configurations::$MANUAL_MANAGER);
                    return response()->download($filename);
                }

            case "2":
                if ($user->power != Configurations::$USER_POWER_TEACHER){
                    return redirect()->back()->withErrors(['msg'=>'NO_USER_POWER_MATCH']);
                }else{
                    $filename = public_path(Configurations::$MANUAL_TEACHER);
                    return response()->download($filename);

                }
        }
    }
}
