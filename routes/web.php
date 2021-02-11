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

Route::get('/', 'MainPageController@index')->name('mainpage');
Route::get('/uk', 'MainPageController@indexUK');

Route::get('/policy', 'MainPageController@policy')->name('policy');

// Auth::routes();
Route::get('login', 'Auth\LoginController@showLoginForm')->name('login');
Route::post('login', 'Auth\LoginController@login');
Route::post('logout', 'API\AuthController@logout');

// роут для реэстрації по інвайту
Route::get('register/{invite}', 'API\AuthViewsController@register');

// роуты администратора
Route::group(['middleware' => 'auth'], function () {
    Route::get('/admin', 'Dash\PublicController@index')->name('admin')->middleware('can:tutor');

    Route::get('/students', 'AdminController@students')->name('students');
    Route::get('/students/{id}', 'AdminController@studentsPage')->name('student');
    Route::get('/tutors', 'AdminController@tutors')->name('tutors');
    Route::get('/tutors/{id}', 'AdminController@tutorsPage')->name('tutor');
    Route::get('/classrooms', 'AdminController@classrooms')->name('classrooms');
    Route::get('/settings', 'AdminController@settings')->name('settings');
    Route::get('/finance', 'AdminController@finance')->name('finance');
    Route::get('/reports', 'AdminController@reports')->name('reports');
    Route::get('/invites', 'AdminController@invites')->name('invites');
    Route::get('/online', 'Dash\VideoChatController@onlineDash')->name('online');
    Route::get('/online/{id}', 'Dash\VideoChatController@onlineRoom')->name('onlineroom');
    Route::post('/online/auth', 'Dash\VideoChatController@auth');
    // options
    Route::post('/option/new', 'OptionsController@create')->name('addoption');
    Route::patch('/option/{id}', 'OptionsController@update')->name('updoption');
});
Route::post(Telegram::getAccessToken(), 'Telegram\BotController@host');
// Route::post(Telegram::getAccessToken(), function () {
//   $update = Telegram::commandsHandler(true);
//   // return 'ok';
// });
