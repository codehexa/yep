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
    Route::get("/userManage",[\App\Http\Controllers\UsersController::class, 'index'])->name('userManage');
    /* academy */
    Route::get("/academyManage",[\App\Http\Controllers\AcademyController::class, 'index'])->name('academyManage');
    Route::post("/addAcademy",[\App\Http\Controllers\AcademyController::class, 'add'])->name("addAcademy");
    Route::post("/getAcademyInfoJson",[\App\Http\Controllers\AcademyController::class, 'infoJson'])->name('getAcademyInfoJson');
    Route::post("/storeAcademy",[\App\Http\Controllers\AcademyController::class, 'store'])->name("storeAcademy");
    Route::post("/deleteCategory",[\App\Http\Controllers\AcademyController::class, 'delete'])->name('deleteCategory');
});
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
