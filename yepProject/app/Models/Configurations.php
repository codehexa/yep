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

    public static $BMS_MAX_MASS_SIZE    = 500;  // 알리고 에서 문자 보내기 대량 최대치
    public static $BMS_SENT_MESSAGE_READY   = "READY";

    public static $BMS_SHEET_INFO_SENT_DATE_READY = "READY";
    public static $BMS_SHEET_INFO_SENT_DATE_SENDING = "SENDING";
    public static $BMS_SHEET_INFO_SENT_DATE_SENT = "SENT";

    public static $ACADEMY_PRESIDENT_CALL   = "PRESIDENT_CALL";

    public static $BMS_PAGE_FUNCTION_KEYS = [
        ["tag"=>":ACADEMY_NAME:","title"=>"(학원)관 이름","type"=>"STRING"],
        ["tag"=>":CLASS_NAME:","title"=>"반 이름","type"=>"STRING"],
        ["tag"=>":TEACHER_NAME:","title"=>"선생님 이름","type"=>"STRING"],
        ["tag"=>":SUBJECT_NAME:","title"=>"수업 과목","type"=>"STRING"],
        ["tag"=>":SEMESTER:","title"=>"학기","type"=>"STRING"],
        ["tag"=>":CURRICULUM:","title"=>"과정","type"=>"STRING"], // 5
        ["tag"=>":HAKGYON_NAME:","title"=>"학년","type"=>"STRING"],
        ["tag"=>":DAYS:","title"=>"수업 요일","type"=>"STRING"],
        ["tag"=>":DAY:","title"=>"해당 요일","type"=>"STRING"],
        ["tag"=>":NOW_WEEK:","title"=>"이번 주","type"=>"STRING"],
        ["tag"=>":PRE_WEEK:","title"=>"이전 주","type"=>"STRING"], // 10
        ["tag"=>":STUDY_TYPE:","title"=>"수업 종류","type"=>"STRING"],
        ["tag"=>":STUDY_TIME:","title"=>"수업 시간","type"=>"STRING"],
        ["tag"=>":TEACHER_SAY:","title"=>"선생님 의견","type"=>"STRING"],
        ["tag"=>":ADD_BOTTOM_ROW:","title"=>"한줄 띄우기","type"=>"STRING"],
        ["tag"=>":ACADEMY_TEL:","title"=>"학원 개별 전화번호","type"=>"STRING"],    // 15
        ["tag"=>":ACADEMY_ALL_TEL:","title"=>"학원 대표 전화번호","type"=>"STRING"],
        ["tag"=>":FN_ARRAY_START:","title"=>"반복 시작","type"=>"ARRAY_START"],
        ["tag"=>":FN_ARRAY_END:","title"=>"반복 끝","type"=>"ARRAY_END"],
        ["tag"=>":FN_CLASS_NOW_WEEK:","title"=>"수업_이번주","type"=>"FUNCTION"],
        ["tag"=>":FN_CLASS_DT:","title"=>"DT","type"=>"FUNCTION"],  // 20
        ["tag"=>":FN_CLASS_BOOK_WORK:","title"=>"교재과제","type"=>"FUNCTION"],
        ["tag"=>":FN_CLASS_OUTPUT_WORK:","title"=>"제출과제","type"=>"FUNCTION"],
        ["tag"=>":NOTICE:","title"=>"공지사항","type"=>"STRING"],
        ["tag"=>":DT_AREA:","title"=>"DT 범위","type"=>"STRING"],

    ];

    public static $BMS_SMS_TAG_CLASSNAME    = ":CLASS_NAME";
    public static $BMS_SMS_TAG_CLASSNAME_TITLE    = "반 이름";
    public static $BMS_SMS_TAG_GREETING = ":GREETING";
    public static $BMS_SMS_TAG_GREETING_TITLE = "맨 상위 인사말";
    public static $BMS_SMS_TAG_BOOK_WORK    = ":BOOK_WORK";
    public static $BMS_SMS_TAG_BOOK_WORK_TITLE    = "교재과제";
    public static $BMS_SMS_TAG_OUTPUT_WORK    = ":OUTPUT_WORK";
    public static $BMS_SMS_TAG_OUTPUT_WORK_TITLE    = "제출과제";
    public static $BMS_SMS_TAG_NOTICE    = ":NOTICE";
    public static $BMS_SMS_TAG_NOTICE_TITLE    = "공지사항";
    public static $BMS_SMS_TAG_ACADEMY_INFO    = ":ACADEMY_INFO";
    public static $BMS_SMS_TAG_ACADEMY_INFO_TITLE    = "학원 정보";

    // SDL CODE
    public static $BMS_BS_CODES = [
        ["tag"=>"NEXT","title"=>"다음 등원일에 제출"],
        ["tag"=>"SAME","title"=>"해당하는 수업시간에 제출"],
        ["tag"=>"BOOK","title"=>"익힘책"],
        ["tag"=>"DIRECT","title"=>"직접 입력"],
    ];
    // SDL CODES.. DB related. caution.
    public static $BMS_BS_CODE_NEXT = "NEXT";
    public static $BMS_BS_CODE_SAME = "SAMEDAY";
    public static $BMS_BS_CODE_BOOK = "BOOK";
    public static $BMS_BS_CODE_DIRECT = "DIRECT";

    // Script TAG
    public static $JS_ENTER = ":ENTER:";
    public static $JS_STRING = ":STRING:";
    public static $JS_SELECT = ":SELECT:";

    // Subject Functions. with related db
    public static $BMS_SUBJECT_FUNCTIONS = [
        ["code"=>"0","title"=>"NORMAL"],
        ["code"=>"1","title"=>"GRAMMAR"],
        ["code"=>"2","title"=>"NOTHING"],
    ];

    // Bbs list limit
    public static $BBS_LIMIT    = 20;
    public static $BBS_TYPE_ALL  = "0";
    public static $BBS_TYPE_NORMAL  = "1";
}
