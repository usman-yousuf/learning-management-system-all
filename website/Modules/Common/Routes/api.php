<?php

use Illuminate\Http\Request;
use Modules\Common\Http\Controllers\API\NotificationsController;
use Modules\Common\Http\Controllers\API\ReportController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/common', function (Request $request) {
    return $request->user();
});

Route::group(['middleware' => 'auth:api'], function () {
    #region - Student Course Report - START
        Route::post('get_student_course_report', [ReportController::class, 'getStudentCourseReport']);
    #endregion - Student Course Report - START

    #region - Sales Report - START
        Route::post('get_sales_report', [ReportController::class, 'getSalesReport']);
    #endregion - Sales Report - START


    #region - Notifications  - START
        Route::post('get_profile_notifications', [NotificationsController::class, 'getProfileNotifications']);
        Route::post('get_unread_notifications_count', [NotificationsController::class, 'getUnReadNotificationCount']);
        Route::post('delete_notification', [NotificationsController::class, 'deleteNotification']);
        Route::post('mark_notification_as_read', [NotificationsController::class, 'markNotificationRead']);
        Route::post('mark_profile_notifications_as_read', [NotificationsController::class, 'markProfileNotificationsRead']);
        Route::post('bulk_delete_notifications', [NotificationsController::class, 'bulkDeleteNotification']);
    #endregion - Notifications  - START

    #region - Activity  - START
        Route::post('get_profile_activities', [NotificationsController::class, 'getProfileNotifications']);
        Route::post('get_unread_activities_count', [NotificationsController::class, 'getUnReadNotificationCount']);
        Route::post('delete_activity', [NotificationsController::class, 'deleteNotification']);
        Route::post('mark_activity_as_read', [NotificationsController::class, 'markNotificationRead']);
        Route::post('mark_profile_activities_as_read', [NotificationsController::class, 'markProfileNotificationsRead']);
        Route::post('bulk_delete_activities', [NotificationsController::class, 'bulkDeleteNotification']);
    #endregion - Notifications  - START


});
