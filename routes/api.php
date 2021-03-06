<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::post('/register', 'API\AuthController@register');
Route::get('/check-name', 'API\AuthViewsController@checkName');

Route::middleware('auth:api')->group(function () {
    Route::post('/logout', 'API\AuthController@logout');
});

// V1 API
Route::group(['prefix' => 'v1'], function () {
  // Захват лидов
  Route::post('lead-push', 'LeadsController@addLead');
  // Promo info
  Route::get('promo', 'PromoController@getPromo');
  // звездный рейтинг
  Route::get('rating-get', 'RatingController@getRating');
  Route::post('rating-set', 'RatingController@setRating');

  // отримуємо уроки
  Route::get('lesson-get', 'Dash\LessonController@getLesson');
  Route::get('lesson-start-data', 'Dash\LessonController@getStartData');

  // Online Chat
  Route::get('online-rooms-get', 'Dash\VideoChatController@getRooms');
  Route::get('online-start-data', 'Dash\VideoChatController@getStartData');
  Route::get('online-room-times', 'Dash\VideoChatController@getRoomTimes');

  // статистика про студента для студента
  Route::get('student-get-profile-stat', 'Dash\StudentController@getStudentSelfStat')->middleware('can:student');

  // група роутів доступна лише адміністратору
  Route::middleware('can:admin')->group(function () {
    // Classrooms
    Route::get('classroom-get', 'Dash\ClassroomController@getClass');
    Route::post('classroom-set', 'Dash\ClassroomController@setClass');
    Route::patch('classroom-upd/{id}', 'Dash\ClassroomController@updClass');
    // Tutors
    Route::get('tutor-get', 'Dash\TutorController@getTutor');
    Route::get('tutor-get-stat', 'Dash\TutorController@getTutorStat');
    Route::post('tutor-set', 'Dash\TutorController@setTutor');
    Route::patch('tutor-upd/{id}', 'Dash\TutorController@updTutor');
    // Students
    Route::get('student-get', 'Dash\StudentController@getStudent');
    Route::get('student-get-stat', 'Dash\StudentController@getStudentStat');
    Route::post('student-set', 'Dash\StudentController@setStudent');
    Route::patch('student-upd/{id}', 'Dash\StudentController@updStudent');
    // Lessons
    Route::post('lesson-set', 'Dash\LessonController@setLesson');
    Route::patch('lesson-upd/{id}', 'Dash\LessonController@updLesson');
    Route::post('lesson-copy/{id}', 'Dash\LessonController@copyLesson');
    Route::delete('lesson-del/{id}', 'Dash\LessonController@delLesson');
    Route::patch('lesson-time-upd/{id}', 'Dash\LessonController@updLessonTime');
    // Finances
    Route::get('finances-get', 'Dash\LogisticController@getFinances');
    Route::post('refill-student', 'Dash\LogisticController@refillStud');
    Route::post('wage-pay', 'Dash\LogisticController@wagePay');
    Route::post('other-pay-add', 'Dash\LogisticController@otherPay');
    // Reports
    Route::get('reports-get', 'Dash\LogisticController@getReports');
    Route::get('stats-get', 'Dash\LogisticController@getStats');
    // KPI
    Route::get('kpi-get', 'Dash\LogisticController@getKPI');    
    // Invites
    Route::get('invites-get', 'Dash\InvitesController@getInvites');
    Route::post('invite-set', 'Dash\InvitesController@setInvite');
    Route::delete('invite-del/{id}', 'Dash\InvitesController@delInvite');
    // Invites
    Route::get('users-get', 'Dash\InvitesController@getUsers');
    Route::delete('user-del/{id}', 'Dash\InvitesController@delUser');
    // Settings
    Route::get('settings-get', 'Dash\SettingsController@getSettings');
    Route::post('setting-set', 'Dash\SettingsController@setSetting');
    Route::patch('setting-upd/{id}', 'Dash\SettingsController@updSetting');
    Route::get('telegram-status-get', 'Dash\SettingsController@getTelegramSettings');
    Route::get('telegram-set-webhook', 'Dash\SettingsController@getTelegramSetWebhook');
    Route::get('telegram-del-webhook', 'Dash\SettingsController@getTelegramDelWebhook');
    Route::get('telegram-get-webhook', 'Dash\SettingsController@getTelegramGetWebhook');

  });

});
