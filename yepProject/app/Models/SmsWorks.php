<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SmsWorks extends Model
{
    use HasFactory;
    protected $table = "smsworks";
    protected $fillable = [
        "ac_id","year","hakgi_id","tf_id","sg_id","cl_id","st_id",
        "tc_opinion","sent","sent_date"
    ];
}
