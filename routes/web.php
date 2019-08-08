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

Route::group(['middleware' => 'auth'], function () {
    Route::any('/', 'IndexController@index')->name('index');
    Route::any('/site/{id}', 'IndexController@pages')->name('site');
    Route::post('/add', 'IndexController@addSite')->name('addSite');
});

Auth::routes(['register' => false]);

//Route::get('/home', 'HomeController@index')->name('home');
