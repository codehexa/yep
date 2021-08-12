<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BmsSettingsController extends Controller
{
    //
    public function index(){
        return view('bms.settings');
    }
}
