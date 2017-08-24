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

Route::get('/', 'NeoController@index');

Route::group(['prefix' => 'neo', 'middleware' => ['accept.header']], function () {

    Route::get('/hazardous', 'NeoController@showHazardous');

    Route::get('/fastest', 'NeoController@showFastest');

    Route::get('/best-year', 'NeoController@showBestYear');

    Route::get('/best-month', 'NeoController@showBestMonth');

});