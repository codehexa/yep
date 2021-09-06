<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class TempController extends Controller
{
    //
    public function tmp(){
        $user = User::find(1);
        $user->password = Hash::make("thsdhstn");

        $user->save();

    }

    public function mail(){
        $to = 'codehexa1@gmail.com';
        $subject = 'Studying sending email in Laravel';
        $data = [
            'title' => 'Hi there, Show me the money, Plz....',
            'body'  => 'This is the body of an email message',
            'user'  => User::find(1)
        ];

        return Mail::send('welcome', $data, function($message) use($to, $subject) {
            $message->to($to)->subject($subject);
        });
    }
}
