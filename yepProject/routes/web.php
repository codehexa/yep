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
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('/s',[\App\Http\Controllers\TempController::class, 'tmp']);
