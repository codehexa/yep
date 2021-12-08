<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
})->name("/");

Route::get("/reg",[\App\Http\Controllers\MyRegisterController::class, "register"]);
Route::post("/regDo",[\App\Http\Controllers\MyRegisterController::class, "regDo"]);
Route::get("/regDone",[\App\Http\Controllers\MyRegisterController::class, "regDone"]);
Route::get("/passwdReset",[\App\Http\Controllers\MyRegisterController::class, "passwdReset"]);
Route::post("/passwdResetDo",[\App\Http\Controllers\MyRegisterController::class, "passwdResetDo"]);

Auth::routes();

/*
 * user manage
 */
Route::get("/myInfo",[\App\Http\Controllers\MyInfoController::class, "info"]);
Route::post("/myInfoEdit",[\App\Http\Controllers\MyInfoController::class, "modify"]);
Route::get("/myPasswdChange",[\App\Http\Controllers\MyInfoController::class, "pwChange"]);
Route::post("/myPwEdit",[\App\Http\Controllers\MyInfoController::class, "pwChangeDo"]);

Route::group(['middleware' => ['adminPower']],function() {
    Route::get("/settings",[\App\Http\Controllers\SettingsController::class, 'index']);
    /* user */
    Route::get("/userView/{id}",[\App\Http\Controllers\UsersController::class,'show'])->name('userView');
    Route::get("/userManage/{name?}",[\App\Http\Controllers\UsersController::class, 'index'])->name('userManage');
    Route::get("/userModify/{id}",[\App\Http\Controllers\UsersController::class, 'modify']);
    Route::post("/userUpdate",[\App\Http\Controllers\UsersController::class, 'store']);
    Route::post("/userPasswordChange",[\App\Http\Controllers\UsersController::class, 'changePw']);
    Route::post("/userAdd",[\App\Http\Controllers\UsersController::class, 'addUser']);
    Route::post("/userStopDo",[\App\Http\Controllers\UsersController::class, "stopUser"]);
    Route::post("/userEmailChange",[\App\Http\Controllers\UsersController::class, "emailChange"]);
    /* academy */
    Route::get("/academyManage/{name?}",[\App\Http\Controllers\AcademyController::class, 'index'])->name('academyManage');
    Route::post("/addAcademy",[\App\Http\Controllers\AcademyController::class, 'add'])->name("addAcademy");
    Route::post("/getAcademyInfoJson",[\App\Http\Controllers\AcademyController::class, 'infoJson'])->name('getAcademyInfoJson');
    Route::post("/storeAcademy",[\App\Http\Controllers\AcademyController::class, 'store'])->name("storeAcademy");
    Route::post("/deleteCategory",[\App\Http\Controllers\AcademyController::class, 'delete'])->name('deleteCategory');

    /* 학기 관리 */
    Route::get("/hakgis/{sgid?}",[\App\Http\Controllers\HakgiController::class, "index"])->name("hakgis");
    Route::post("/addHakgi",[\App\Http\Controllers\HakgiController::class, "add"]);
    Route::post("/hakgiInfoJson",[\App\Http\Controllers\HakgiController::class, "getInfo"]);
    Route::post("/storeHakgi",[\App\Http\Controllers\HakgiController::class, "store"]);
    Route::post("/delHakgi",[\App\Http\Controllers\HakgiController::class, "delete"]);

    /* 학년관리 */
    Route::get("/schoolGrades",[\App\Http\Controllers\schoolGradesController::class, 'index'])->name('schoolGrades');
    Route::post("/schoolGradePost",[\App\Http\Controllers\schoolGradesController::class, 'add']);
    Route::post("/schoolGradeIndex",[\App\Http\Controllers\schoolGradesController::class, 'move']);
    Route::post("/schoolGradeJson",[\App\Http\Controllers\schoolGradesController::class, 'info']);
    Route::post("/schoolGradeStore",[\App\Http\Controllers\schoolGradesController::class, 'store']);
    Route::post("/schoolGradeDelete",[\App\Http\Controllers\schoolGradesController::class, 'delete']);

    /* 옵션관리 */
    Route::get("/options",[\App\Http\Controllers\SettingsController::class, 'options'])->name('options');
    Route::post("/optionGetJson",[\App\Http\Controllers\SettingsController::class, 'getOptionJson']);
    Route::post("/optionUpdate",[\App\Http\Controllers\SettingsController::class, 'optionUpdate']);
    Route::get("/optionsLog",[\App\Http\Controllers\LogOptionsController::class, 'optionLogList'])->name('optionLogList');

    /* 로그관리 */
    Route::get("/logs",[\App\Http\Controllers\LogsViewController::class, 'all'])->name('logsView');
    Route::get("/logsAcademy",[\App\Http\Controllers\LogsViewController::class, 'academy'])->name('logsAcademy');
    Route::get("/logsUsers",[\App\Http\Controllers\LogsViewController::class, 'users'])->name('logsUsers');

    /* SMS Page */
    Route::get("/smsPageSet",[\App\Http\Controllers\SettingsController::class,"SmsPageSet"])->name("smsPageSet");
    Route::post("/smsPageSetSave",[\App\Http\Controllers\SettingsController::class,"SmsPageSetSave"]);
});

/* Manager Power */
Route::group(['middleware' => ['managerPower']],function() {
    /* 반 관리 */
    Route::get("/classes/{acaid?}",[\App\Http\Controllers\ClassesController::class, "index"])->name("classes");
    Route::post("/addClass",[\App\Http\Controllers\ClassesController::class, "add"]);
    Route::post("/classInfoJson",[\App\Http\Controllers\ClassesController::class, "infoJson"]);
    Route::post("/storeClass",[\App\Http\Controllers\ClassesController::class, "store"]);
    Route::post("/setClassTeacher",[\App\Http\Controllers\ClassesController::class, "setClassTeacher"]);
    Route::post("/getTeachersJson",[\App\Http\Controllers\ClassesController::class, "getTeacher"]);
    Route::post("/delClass",[\App\Http\Controllers\ClassesController::class, 'delete']);


    /* 시험주 관리 */
    Route::get("/testWeeks/{year?}/{grade?}/{hakgi?}",[\App\Http\Controllers\TestWeeksController::class, "list"])->name("testweeks");
    Route::post("/getHakgiListJson",[\App\Http\Controllers\TestWeeksController::class, "getHakgiListJson"]);
    Route::post("/addTestWeek",[\App\Http\Controllers\TestWeeksController::class, "add"]);
    Route::post("/testWeekJson",[\App\Http\Controllers\TestWeeksController::class, "testWeekJson"]);
    Route::post("/storeTestWeek",[\App\Http\Controllers\TestWeeksController::class, "storeTestWeek"]);
    Route::post("/delTestWeek",[\App\Http\Controllers\TestWeeksController::class, "delTestWeek"]);

    /* 시험 관리 -> 과목 -> 취소 함 */
    Route::get("/testAreas/{grade?}",[\App\Http\Controllers\TestAreasController::class,"list"])->name("testAreas");
    Route::post("/addTestArea",[\App\Http\Controllers\TestAreasController::class, "add"]);
    Route::post("/testAreaJson",[\App\Http\Controllers\TestAreasController::class, "info"]);
    Route::post("/delTestArea",[\App\Http\Controllers\TestAreasController::class, "del"]);
    Route::post("/storeTestArea",[\App\Http\Controllers\TestAreasController::class, "store"]);

    /* 과목관리 */
    /*Route::get("/kwamoks/{grade?}",[\App\Http\Controllers\LnCurriSubjectController::class, "index"])->name('kwamoks');*/
    Route::get("/subjects/{grade?}",[\App\Http\Controllers\SubjectsController::class, "index"])->name('subjects');
    Route::post("/addSubject",[\App\Http\Controllers\SubjectsController::class, "add"]);
    Route::post("/addSubjectGroupJson",[\App\Http\Controllers\SubjectsController::class, "addGroup"]);
    Route::post("/storeSubject",[\App\Http\Controllers\SubjectsController::class,"store"]);
    Route::post("/getSubjectJson",[\App\Http\Controllers\SubjectsController::class,"getSubjectJson"]);
    Route::post("/delSubject",[\App\Http\Controllers\SubjectsController::class,"deleteSubject"]);
    Route::post("/updateOrderSubjects",[\App\Http\Controllers\SubjectsController::class, "updateOrderSubjects"]);

    /* 코멘트 관리 */
    Route::get("/comments/{grade?}/{sjId?}",[\App\Http\Controllers\CommentsController::class,"index"])->name("comments");
    Route::post("/setComments",[\App\Http\Controllers\CommentsController::class, "setComment"]);
    Route::post("/getCommentJson",[\App\Http\Controllers\CommentsController::class, "getInfoJson"]);
    Route::post("/storeComment",[\App\Http\Controllers\CommentsController::class, "storeComment"]);
    Route::post("/delComments",[\App\Http\Controllers\CommentsController::class, "delete"]);


    /* 학생 관리 */
    Route::get("/students/{live?}/{acId?}/{clId?}",[\App\Http\Controllers\StudentsController::class,"index"])->name("students");
    Route::get("/studentsSearch/{field}/{key}",[\App\Http\Controllers\StudentsController::class,"studentsSearch"]);
    Route::post("/excelFileUpload",[\App\Http\Controllers\StudentsController::class, "fileUpload"]);
    Route::get("/testExcel",[\App\Http\Controllers\StudentsController::class, "testExcel"]);
    Route::post("/getStudentInfoJson",[\App\Http\Controllers\StudentsController::class, "getStudentJson"]);
    Route::post("/storeStudent",[\App\Http\Controllers\StudentsController::class, "store"]);
    Route::post("/getClassesJsonInStudent",[\App\Http\Controllers\StudentsController::class, "getClasses"]);
    Route::post("/studentReset",[\App\Http\Controllers\StudentsController::class,"studentReset"]);

    /* 시험 폼 관리 */
    Route::get("/testForm/{grade?}",[\App\Http\Controllers\TestFormsController::class, "index"]);
    Route::post("/testFormSubjectsJson",[\App\Http\Controllers\TestFormsController::class, "getSubjects"]);
    Route::post("/storeTestForm",[\App\Http\Controllers\TestFormsController::class, "store"]);
    Route::post("/getTestFormJson",[\App\Http\Controllers\TestFormsController::class, "getTestFormData"]);
    Route::post("/getTestFormsJson",[\App\Http\Controllers\TestFormsController::class, "getTestForms"]);
    Route::post("/delTestForms",[\App\Http\Controllers\TestFormsController::class, "deleteForm"]);
});

/* SMS 업무 */
Route::get("/SmsFront/{acId?}/{gradeId?}/{clid?}/{year?}/{hakgi?}/{week?}",[\App\Http\Controllers\SmsJobController::class,"front"]);
Route::get("/SmsJob/{acId?}/{gradeId?}/{classId?}/{tfId?}/{year?}/{hakgi?}/{weeks?}",[\App\Http\Controllers\SmsJobController::class, "index"]);
Route::get("/SmsJobInput/{spId}",[\App\Http\Controllers\SmsJobController::class,"SmsJobInput"]);
Route::post("/SmsJobSave",[\App\Http\Controllers\SmsJobController::class, "saveSmsJob"]);
Route::post("/SmsPaperSetDone",[\App\Http\Controllers\SmsPapersController::class, "SmsPaperSetDone"]);
Route::post("/SmsCheckToSend",[\App\Http\Controllers\SmsPapersController::class, "SmsCheckToSend"]);
Route::post("/getHakgisAllJson",[\App\Http\Controllers\SmsJobController::class,"getHakgisHandler"]);
Route::post("/getFormsJson",[\App\Http\Controllers\SmsJobController::class, "getFormsJson"]);
Route::post("/getClassesJson",[\App\Http\Controllers\SmsJobController::class, "getClassesJson"]);
Route::post("/getTestFormsInSmsJson",[\App\Http\Controllers\SmsJobController::class, "getTestFormsJson"]);
Route::post("/sendSms",[\App\Http\Controllers\SmsJobController::class, "sendSms"]);
Route::post("/saveOpinion",[\App\Http\Controllers\SmsJobController::class, "saveOpinion"]);
Route::post("/saveWordian",[\App\Http\Controllers\SmsJobController::class, "saveWordian"]);
Route::post("/saveSmsEach",[\App\Http\Controllers\SmsJobController::class, "saveSmsEach"]);
Route::post("/saveMatching",[\App\Http\Controllers\SmsJobController::class, "saveMatch"]);
Route::post("/delSmsFront",[\App\Http\Controllers\SmsPapersController::class, "delSmsFront"]);
Route::post("/getTestPapersJson",[\App\Http\Controllers\SmsPapersController::class,"getTestPapers"]);
Route::post("/addSmsPapers",[\App\Http\Controllers\SmsPapersController::class, "addSmsPapers"]);
Route::get("/SmsExcelDownload/{ppId}",[\App\Http\Controllers\SmsJobController::class, "SmsExcelDownload"]);
Route::post("/getTempPage",[\App\Http\Controllers\SmsJobController::class,"tempGet"]);


Route::prefix('bms')->group(function(){
    Route::get("/front",[\App\Http\Controllers\BmsController::class, "index"]);
    Route::get("/settings",[\App\Http\Controllers\BmsController::class, "settings"]);
    Route::post("/addStudyTypesJs",[\App\Http\Controllers\BmsController::class, "addStudyTypesJs"]);
    Route::post("/saveSortStudyTypes",[\App\Http\Controllers\BmsController::class, "saveSortStudyTypes"]);
    Route::post("/saveStudyTypeJs",[\App\Http\Controllers\BmsController::class, "saveStudyTypeJs"]);
    Route::post("/storeStudyTypeJs",[\App\Http\Controllers\BmsController::class, "storeStudyTypeJs"]);
    Route::post("/deleteStudyTypeJs",[\App\Http\Controllers\BmsController::class, "deleteStudyTypeJs"]);
    Route::post("/addStudyDayJs",[\App\Http\Controllers\BmsController::class, "addStudyDayJs"]);
    Route::post("/saveStudyDayJs",[\App\Http\Controllers\BmsController::class, "saveStudyDayJs"]);
    Route::post("/sortStudyDayJs",[\App\Http\Controllers\BmsController::class, "sortStudyDayJs"]);
    Route::post("/deleteStudyDayJs",[\App\Http\Controllers\BmsController::class, "deleteStudyDayJs"]);
    Route::post("/addStudyTimeJs", [\App\Http\Controllers\BmsController::class, "addStudyTimeJs"]);
    Route::post("/saveStudyTimeJs", [\App\Http\Controllers\BmsController::class, "saveStudyTimeJs"]);
    Route::post("/deleteStudyTimeJs", [\App\Http\Controllers\BmsController::class, "deleteStudyTimeJs"]);
    Route::post("/saveSortStudyTimesJs", [\App\Http\Controllers\BmsController::class, "saveSortStudyTimesJs"]);

    Route::get("/sending/{year?}/{month?}",[\App\Http\Controllers\BmsSendLogController::class, "index"]);

    /* subjects */
    Route::get("/subjects/{sgid?}",[\App\Http\Controllers\BmsSubjectsController::class, "index"]);
    Route::post("/addSubjectJson",[\App\Http\Controllers\BmsSubjectsController::class, "add"]);
    Route::post("/delSubjectJson",[\App\Http\Controllers\BmsSubjectsController::class, "del"]);
    Route::post("/storeSubjectJson",[\App\Http\Controllers\BmsSubjectsController::class, "modifySubject"]);
    /* hwork manage */
    Route::get("/hworks/{sgid?}",[\App\Http\Controllers\BmsHworksController::class, "index"]);
    Route::get("/hworkInput/{hwid?}",[\App\Http\Controllers\BmsHworksController::class, 'inputPage']);
    Route::post("/hworkSave",[\App\Http\Controllers\BmsHworksController::class, "addClass"]);
    Route::post("/hworkStore",[\App\Http\Controllers\BmsHworksController::class, "storeClass"]);
    Route::post("/getHworkSubjects",[\App\Http\Controllers\BmsHworksController::class, "getSubjects"]);

    Route::post("/addCurrculum",[\App\Http\Controllers\BmsCurriculumsController::class, "addCurri"]);
    Route::post("/saveCurriculumJs",[\App\Http\Controllers\BmsCurriculumsController::class, "saveCurriculum"]);
    Route::post("/deleteCurriculumJs",[\App\Http\Controllers\BmsCurriculumsController::class, "deleteCurriculum"]);
    Route::post("/saveSortCurriculumsJs",[\App\Http\Controllers\BmsCurriculumsController::class, "saveSortCurriculumsJs"]);
    Route::post("/addHworks",[\App\Http\Controllers\BmsHworksController::class, "addClass"]);
    Route::post("/addBmsSubject",[\App\Http\Controllers\BmsSubjectsController::class, "addSubject"]);
    Route::post("/modifySubject",[\App\Http\Controllers\BmsSubjectsController::class, "modifySubject"]);
    Route::post("/saveOrderSubject",[\App\Http\Controllers\BmsSubjectsController::class, "saveOrder"]);
    Route::post("/getSubjects",[\App\Http\Controllers\BmsSubjectsController::class, "getSubjects"]);
    Route::post("/addWorkbook",[\App\Http\Controllers\BmsWorkbooksController::class, "addWorkbook"]);
    Route::post("/saveSortWorkbook",[\App\Http\Controllers\BmsWorkbooksController::class, "saveSortWorkbooks"]);
    Route::post("/storeWorkbook",[\App\Http\Controllers\BmsWorkbooksController::class, "storeWorkbook"]);
    Route::post("/deleteWorkbook",[\App\Http\Controllers\BmsWorkbooksController::class, "deleteWorkbook"]);

    /* 세팅 */
    Route::get("/basic",[\App\Http\Controllers\BmsBasicController::class, "index"]);
    Route::post("/getTeachers",[\App\Http\Controllers\BmsBasicController::class, "getTeachers"]);
    Route::post("/getHakgis",[\App\Http\Controllers\BmsBasicController::class, "getHakgis"]);
    Route::post("/basicLoadSheet",[\App\Http\Controllers\BmsBasicController::class, "basicLoadSheet"]);
    Route::post("/basicInfoSave",[\App\Http\Controllers\BmsBasicController::class, "saveInfo"]);
    Route::post("/getBasicPageJson",[\App\Http\Controllers\BmsBasicController::class, "getBasicPageJson"]);
    Route::post("/getHworkValues",[\App\Http\Controllers\BmsBasicController::class, "getHworkValues"]);
    Route::post("/getStudentsJson",[\App\Http\Controllers\BmsBasicController::class, "getStudentJson"]);
    Route::post("/saveToBmsSend",[\App\Http\Controllers\BmsBasicController::class,"saveToBmsSend"]);

    /* editor */
    Route::get("/editor",[\App\Http\Controllers\BmsEditorController::class, "index"]);
    Route::post("/editorAddBasic",[\App\Http\Controllers\BmsEditorController::class,"addBasic"]);
    Route::post("/editorGetClasses",[\App\Http\Controllers\BmsEditorController::class, "getClasses"]);
    Route::post("/editorSheetItemSave",[\App\Http\Controllers\BmsEditorController::class, "saveSheetItems"]);
    Route::post("/editorLoadSheet",[\App\Http\Controllers\BmsEditorController::class, "loadSheet"]);
    Route::post("/editorPreviewer",[\App\Http\Controllers\BmsEditorController::class, "preview"]);

    /* mms page settings */
    Route::get("/pageSetting",[\App\Http\Controllers\BmsPageSettingsController::class, "index"]);
    Route::post("/pageAddSetting",[\App\Http\Controllers\BmsPageSettingsController::class, "addSetting"]);
    Route::post("/pageStoreSetting",[\App\Http\Controllers\BmsPageSettingsController::class, "storeSetting"]);
    Route::post("/saveSort",[\App\Http\Controllers\BmsPageSettingsController::class, "saveSort"]);
    Route::post("/delPageSetting",[\App\Http\Controllers\BmsPageSettingsController::class, "deleteDo"]);

    /* bms BBS */
    Route::get("/bbs",[\App\Http\Controllers\BmsBbsController::class, "index"]);
    Route::get("/bbspost",[\App\Http\Controllers\BmsBbsController::class, "post"]);
    Route::post("/addBbs",[\App\Http\Controllers\BmsBbsController::class, "add"]);
    Route::post("/addMent",[\App\Http\Controllers\BmsBbsController::class, "addMent"]);
    Route::get("/bbsView/{id?}",[\App\Http\Controllers\BmsBbsController::class,"view"]);
    Route::get("/bbsModify/{id?}",[\App\Http\Controllers\BmsBbsController::class,"edit"]);
    Route::post("/storeBbs",[\App\Http\Controllers\BmsBbsController::class, "modify"]);
    Route::post("/delAddedMent",[\App\Http\Controllers\BmsBbsController::class, "delMent"]);
});

/* SMS 외부에서 확인하는 라우트 */
Route::get("/sms/viewpage/{code?}",[\App\Http\Controllers\SmsViewController::class, "smsView"]);
Route::post("/sms/views",[\App\Http\Controllers\SmsViewController::class, "viewDetail"]);
Route::post("/sms/preview",[\App\Http\Controllers\SmsViewController::class, "viewDetailPreview"]);


/* Manuals */
Route::get("/manuals/show_manual/{n}",[\App\Http\Controllers\ManualsController::class, "showDown"]);

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get("/mail",[\App\Http\Controllers\TempController::class, "mail"]);
Route::get("/smsSendTest",[\App\Http\Controllers\TempController::class, "sms"]);

