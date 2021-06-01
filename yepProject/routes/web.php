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

Auth::routes();

Route::group(['middleware' => ['adminPower']],function() {
    Route::get("/settings",[\App\Http\Controllers\SettingsController::class, 'index']);
    /* user */
    Route::get("/userView/{id}",[\App\Http\Controllers\UsersController::class,'show'])->name('userView');
    Route::get("/userManage",[\App\Http\Controllers\UsersController::class, 'index'])->name('userManage');
    Route::get("/userModify/{id}",[\App\Http\Controllers\UsersController::class, 'modify']);
    Route::post("/userUpdate",[\App\Http\Controllers\UsersController::class, 'store']);
    Route::post("/userPasswordChange",[\App\Http\Controllers\UsersController::class, 'changePw']);
    /* academy */
    Route::get("/academyManage",[\App\Http\Controllers\AcademyController::class, 'index'])->name('academyManage');
    Route::post("/addAcademy",[\App\Http\Controllers\AcademyController::class, 'add'])->name("addAcademy");
    Route::post("/getAcademyInfoJson",[\App\Http\Controllers\AcademyController::class, 'infoJson'])->name('getAcademyInfoJson');
    Route::post("/storeAcademy",[\App\Http\Controllers\AcademyController::class, 'store'])->name("storeAcademy");
    Route::post("/deleteCategory",[\App\Http\Controllers\AcademyController::class, 'delete'])->name('deleteCategory');

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

    /* 학기 관리 */
    Route::get("/hakgis",[\App\Http\Controllers\HakgiController::class, "index"])->name("hakgis");
    Route::post("/addHakgi",[\App\Http\Controllers\HakgiController::class, "add"]);
    Route::post("/hakgiInfoJson",[\App\Http\Controllers\HakgiController::class, "getInfo"]);
    Route::post("/storeHakgi",[\App\Http\Controllers\HakgiController::class, "store"]);
    Route::post("/delHakgi",[\App\Http\Controllers\HakgiController::class, "delete"]);

    /* 시험주 관리 */
    Route::get("/testWeeks/{year?}/{grade?}/{hakgi?}",[\App\Http\Controllers\TestWeeksController::class, "list"])->name("testweeks");
    Route::post("/getHakgiListJson",[\App\Http\Controllers\TestWeeksController::class, "getHakgiListJson"]);
    Route::post("/addTestWeek",[\App\Http\Controllers\TestWeeksController::class, "add"]);
    Route::post("/testWeekJson",[\App\Http\Controllers\TestWeeksController::class, "testWeekJson"]);
    Route::post("/storeTestWeek",[\App\Http\Controllers\TestWeeksController::class, "storeTestWeek"]);
    Route::post("/delTestWeek",[\App\Http\Controllers\TestWeeksController::class, "delTestWeek"]);

    /* 시험 관리 */
    Route::get("/testAreas",[\App\Http\Controllers\TestAreasController::class,"list"])->name("testAreas");
    Route::post("/addTestArea",[\App\Http\Controllers\TestAreasController::class, "add"]);
    Route::post("/testAreaJson",[\App\Http\Controllers\TestAreasController::class, "info"]);
    Route::post("/delTestArea",[\App\Http\Controllers\TestAreasController::class, "del"]);
    Route::post("/storeTestArea",[\App\Http\Controllers\TestAreasController::class, "store"]);

    /* 코멘트 관리 */
    //Route::get("/comments",[Comments])
});
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('/s',[\App\Http\Controllers\TempController::class, 'tmp']);
