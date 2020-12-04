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
Route::get('/comm-admin', 'CommitteeController@getComms');
Route::get('/charge-admin', 'ListController@getChargeMembership');
Route::get('/community-members-admin', 'ListController@getCommunityMembers');
Route::get('/rank-admin', 'ListController@getRank');

Route::get('/committee/form', 'CommitteeController@create');
Route::get('/committee/add', 'CommitteeController@create');
Route::post('/committee/add', 'CommitteeController@store');

Route::get('/committee/assign/{cid}', 'MembersController@index')->name('comm.assign');

Route::get('/committee/members/add', 'MembersController@create')->name('members.add');
Route::post('/committee/members/add', 'MembersController@store')->name('members.add');
