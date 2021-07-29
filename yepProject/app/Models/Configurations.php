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
    public static $SETTINGS_TEST_MAX_SCORE  = "MAX_SCORE_CODE";

    public static $ACADEMY_PRESIDENT_TEL    = "1544-0709";

    public static $SCHOOL_PRE_GRADE_KINDER  = "K";
    public static $SCHOOL_PRE_GRADE_ELEMENT  = "E";
    public static $SCHOOL_PRE_GRADE_MIDDLE  = "M";
    public static $SCHOOL_PRE_GRADE_HIGH  = "H";
    public static $SCHOOL_PRE_GRADE_UNIVERSITY  = "U";
    public static $SCHOOL_PRE_GRADE_KINDER_HAN  = "유치원";
    public static $SCHOOL_PRE_GRADE_ELEMENT_HAN  = "초등학교";
    public static $SCHOOL_PRE_GRADE_MIDDLE_HAN  = "중학교";
    public static $SCHOOL_PRE_GRADE_HIGH_HAN  = "고등학교";
    public static $SCHOOL_PRE_GRADE_UNIVERSITY_HAN  = "대학(교)";

    public static $SCHOOL_PRE_GRADES = [
        ["name"=>"유치원","value"=>"K"],
        ["name"=>"초등학교","value"=>"E"],
        ["name"=>"중학교","value"=>"M"],
        ["name"=>"고등학교","value"=>"H"],
        ["name"=>"대학(교)","value"=>"U"],
    ];

    public static $EMAIL_SENDER = "noreply@yep.org";


    /* excel file upload ... in filesystems.php   name: excels */
    public static $EXCEL_FOLDER = "excels";

    public static $TEST_FORM_IN_SUBJECT_PREFIX = "sj_";
    public static $TEST_FORM_IN_SUBJECT_MAX = 20;
    public static $TEST_SCORES_FIELD_PREFIX = "score_";

    // SMS _paper status
    public static $SMS_STATUS_SAVING    = "SAVING";
    public static $SMS_STATUS_SENT  = "SENT";
    public static $SMS_STATUS_READY = "READY";
    public static $SMS_STATUS_ABLE = "ABLE";

    // SMS_SEND_Results status
    public static $SMS_SEND_RESULTS_READY   = "READY";
    public static $SMS_SEND_RESULTS_SENDING   = "SENDING";
    public static $SMS_SEND_RESULTS_SENT   = "SENT";
    public static $SMS_SEND_VIEW_Y  = "Y";
    public static $SMS_SEND_VIEW_N  = "N";

    public static $SMS_REPLACE_NAME = "'학생이름'";
    public static $SMS_PAGE_URL = "http://yep.localhost/sms/viewpage/";

    // Manual file name
    public static $MANUAL_ADMIN = "storage/manuals/manual_admin.pdf";
    public static $MANUAL_MANAGER = "storage/manuals/manual_manager.pdf";
    public static $MANUAL_TEACHER = "storage/manuals/manual_teacher.pdf";

    // BMS
    // 수업 종류
    public static $STUDY_TYPES  = [
        ["title"=>"Zoom 온라인 수업","value"=>"0"],
        ["title"=>"대면수업","value"=>"1"],
        ["title"=>"수업","value"=>"2"]
    ];

    public static $BMS_WEEKS_COUNT  = "12";

    public static $ACADEMY_PRESIDENT_CALL   = "PRESIDENT_CALL";

    // Script TAG
    public static $JS_ENTER = ":ENTER:";
    public static $JS_STRING = ":STRING:";
    public static $JS_SELECT = ":SELECT:";
}
