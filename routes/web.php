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

Route::get('/', 'Site\SiteController@ebay');

//
Route::get('site/ebay', 'Site\SiteController@ebay');
Route::post('site/doEbay', 'Site\SiteController@doEbay');
Route::get('site/wish', 'Site\SiteController@wish');
Route::post('site/doWish', 'Site\SiteController@doWish');
