<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SmsPageSettings extends Model
{
    use HasFactory;

    protected $table = "sms_page_settings";
    protected $fillable = [
        "greetings","result_link_url","blog_link_url","teacher_title",
        "sps_opt_1","sps_opt_2","sps_opt_3","sps_opt_4","sps_opt_5",
        "blog_guide","use_top","use_bottom"
    ];
}
