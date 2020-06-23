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

// роуты администратора
Route::group(['middleware' => 'auth'], function () {
    Route::get('/admin', 'AdminController@index')->name('admin');
    // options
    Route::post('/option/new', 'OptionsController@create')->name('addoption');
    Route::patch('/option/{id}', 'OptionsController@update')->name('updoption');
});
