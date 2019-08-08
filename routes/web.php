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

Route::any('/', 'Controller@index')->name('index');
Route::any('/site/{id}', 'Controller@pages')->name('site');
Route::post('/add', 'Controller@addSite')->name('addSite');
