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
});
