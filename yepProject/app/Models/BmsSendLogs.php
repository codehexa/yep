<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BmsSendLogs extends Model
{
    use HasFactory;
    protected $table = "bms_send_logs";
    protected $fillable = [
        "tels","bsl_title","bsl_sent_date",
        "bsl_us_id","bsl_us_name",
        "bsl_fault_msg","bsl_result_msg","bsl_usage_point","bsl_aligo_result_code",
        "bsl_reservation_code","bsl_reservation_date","bsl_reservation_time"
    ];

    public function sentUser(){
        return $this->hasOne(User::class,"id","bsl_us_id");
    }
}
