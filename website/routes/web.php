<?php

use App\Http\Controllers\Controller;

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

Route::any('/', [Controller::class, 'welcome'])->name('welcome');
Route::get('/home', [Controller::class, 'welcome'])->name('home');
Route::get('our-teachers', [Controller::class, 'ourTeachers'])->name('ourTeachers');
Route::get('our-courses', [Controller::class, 'ourCourses'])->name('ourCourses');
Route::any('contact-us', [Controller::class, 'contactUs'])->name('contactUs');


Route::group(['middleware' => 'auth'], function () {
    Route::group(['prefix' => 'errors', 'as' => 'errors.'], function () {
        Route::get('403', function () {
            return view('common::errors.403', ['message' => 'You are not Authorized to access this area']);
        })->name('403');

        Route::get('202', function () {
            return view('common::errors.202', ['message' => 'Please Wait while Admin Approves your Account Request']);
        })->name('202');
    });
});
