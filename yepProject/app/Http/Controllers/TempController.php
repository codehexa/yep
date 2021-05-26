<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class TempController extends Controller
{
    //
    public function tmp(){
        $user = User::find(1);
        $user->password = Hash::make("thsdhstn");

        $user->save();

    }
}
