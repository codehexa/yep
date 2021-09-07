<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SmsSendResults extends Model
{
    use HasFactory;

    protected $table = "sms_send_results";
    protected $fillable = [
        "student_id","class_id","sms_paper_code","sms_msg",
        "ssr_status","sms_tel_no","ssr_view"
    ];

    public function Student(){
        return $this->hasOne(Students::class,"id","student_id");
    }
}
