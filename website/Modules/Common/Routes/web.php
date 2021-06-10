<?php

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

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;
use Modules\Common\Http\Controllers\ActivityController;
use Modules\Common\Http\Controllers\API\DocumentController;
use Modules\Common\Http\Controllers\CommonController;
use Modules\Common\Http\Controllers\NotificationController;
use Modules\Common\Http\Controllers\ReportController;

Route::get('/clear-cache', function () {
    Artisan::call('cache:clear');
    Artisan::call('route:clear');
    Artisan::call('view:clear');

    Artisan::call('route:cache');
    Artisan::call('config:cache');
    Artisan::call('optimize:clear');

    return 'Routes cache cleared';
});

Route::prefix('common')->group(function() {
    Route::get('/', 'CommonController@index');
    Route::post('upload-files', [DocumentController::class, 'uploadFiles'])->name('uploadFiles');
});



Route::group(['middleware' => 'auth'], function () {

    // Report Routes
    Route::group(['as' => 'report.'], function () {
        Route::any('general-report', [ReportController::class, 'report'])->name('general');
        Route::any('sales-report', [ReportController::class, 'salesReport'])->name('sales');
    });

    // cms Routes
    Route::group(['as' => 'cms.'], function () {
        Route::any('about-us', [CommonController::class, 'aboutUs'])->name('about-us');
        Route::any('privacy-policy', [CommonController::class, 'privacyPolicy'])->name('privacy-policy');
    });

    Route::group(['as' => 'notifications.'], function () {
        Route::any('notifications', [NotificationController::class, 'index'])->name('index');
        Route::any('notifications-read/{uuid}', [NotificationController::class, 'read'])->name('read');
        Route::any('notifications-delete/{uuid}', [NotificationController::class, 'delete'])->name('delete');
    });

    Route::group(['as' => 'activity.'], function () {
        Route::any('activities', [ActivityController::class, 'index'])->name('index');
        Route::any('mark-activitiy-as-read/{uuid}', [ActivityController::class, 'read'])->name('read');
        Route::any('mark-all-activitiy-as-read', [ActivityController::class, 'read'])->name('read-all');
        Route::any('delete-activity/{uuid}', [ActivityController::class, 'delete'])->name('delete');

        Route::any('get-activity/{uuid}', [ActivityController::class, 'getActivity'])->name('get-activity');
    });
});

