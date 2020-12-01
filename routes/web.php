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

Auth::routes();

Route::redirect('home', '/');
Route::get('/', 'HomeController@index')->name('home');
Route::get('/committee', 'CommitteeController@index')->name('committee');
Route::get('/list', 'ListController@index')->name('list');

Route::get('/comm-search', 'HomeController@ajax');
Route::get('/comm-admin', 'CommitteeController@ajax');
Route::get('/charge-admin', 'ListController@getChargeMembership');
Route::get('/community-members-admin', 'ListController@getCommunityMembers');
Route::get('/rank-admin', 'ListController@getRank');