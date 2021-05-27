<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Configurations extends Model
{
    use HasFactory;

    public static $USER_POWER_ADMIN = "ADMIN";
    public static $USER_POWER_MANAGER   = "MANAGER";
    public static $USER_POWER_TEACHER   = "TEACHER";

    public static $SETTINGS_PAGE_LIMIT_CODE = "PAGE_CODE";

    public static $SCHOOL_PRE_GRADE_KINDER  = "K";
    public static $SCHOOL_PRE_GRADE_ELEMENT  = "E";
    public static $SCHOOL_PRE_GRADE_MIDDLE  = "M";
    public static $SCHOOL_PRE_GRADE_HIGH  = "H";
    public static $SCHOOL_PRE_GRADE_UNIVERSITY  = "U";

    public static $SCHOOL_PRE_GRADES = [
        ["name"=>"유치원","value"=>"K"],
        ["name"=>"초등학교","value"=>"E"],
        ["name"=>"중학교","value"=>"M"],
        ["name"=>"고등학교","value"=>"H"],
        ["name"=>"대학(교)","value"=>"U"],
    ];
}
