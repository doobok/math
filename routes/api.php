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

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

// V1 API
Route::group(['prefix' => 'v1'], function () {
  // Захват лидов
  Route::post('lead-push', 'LeadsController@addLead');
  // Promo info
  Route::get('promo', 'PromoController@getPromo');
  // звездный рейтинг
  Route::get('rating-get', 'RatingController@getRating');
  Route::post('rating-set', 'RatingController@setRating');
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
  Route::get('lesson-get', 'Dash\LessonController@getLesson');
  Route::get('lesson-start-data', 'Dash\LessonController@getStartData');
  Route::post('lesson-set', 'Dash\LessonController@setLesson');
  Route::patch('lesson-upd/{id}', 'Dash\LessonController@updLesson');
  Route::post('lesson-copy/{id}', 'Dash\LessonController@copyLesson');
  Route::delete('lesson-del/{id}', 'Dash\LessonController@delLesson');
  Route::patch('lesson-time-upd/{id}', 'Dash\LessonController@updLessonTime');
  // Finances
  Route::get('finances-get', 'Dash\LogisticController@getFinances');
  Route::post('refill-student', 'Dash\LogisticController@refillStud');
  Route::post('wage-pay', 'Dash\LogisticController@wagePay');
  // Reports
  Route::get('reports-get', 'Dash\LogisticController@getReports');

});
