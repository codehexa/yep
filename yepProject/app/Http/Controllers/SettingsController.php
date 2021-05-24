<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SettingsController extends Controller
{
    //
    public function __construct(){
        $this->middleware('adminPower');
    }
    public function index(){
        return view('pages.settings');
    }
}
