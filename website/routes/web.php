<?php

use App\Http\Controllers\Controller;
use App\Http\Controllers\Teacher\TeacherController;
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

Route::get('/', [Controller::class, 'welcome'])->name('welcome');
Route::get('/home', [Controller::class, 'welcome'])->name('home');


// Teacher Routes
Route::group(['prefix' => 'teacher', 'as' => 'teacher.'], function(){
    Route::get('/dashboard', [TeacherController::class, 'dashboard'])->name('dashboard');
});
