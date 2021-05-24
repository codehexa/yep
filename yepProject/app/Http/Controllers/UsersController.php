<?php

namespace App\Http\Controllers;

use App\Models\Configurations;
use App\Models\Settings;
use App\Models\User;
use Illuminate\Http\Request;

class UsersController extends Controller
{
    //
    public function __construct(){
        $this->middleware('adminPower');
    }

    public function index(){
        $optValues = Settings::where('set_code','=',Configurations::$SETTINGS_PAGE_LIMIT_CODE)->first();
        $limitCount = $optValues->set_value;

        $users = User::orderBy('name','asc')->paginate($limitCount);

        return view('pages.users',['data'=>$users]);
    }
}
