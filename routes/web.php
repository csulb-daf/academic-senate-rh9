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
Route::get('/comm-search', 'HomeController@ajax');

Route::get('/committee', 'CommitteeController@index')->name('committee');
Route::get('/comm-admin', 'CommitteeController@getComms');
Route::get('/committee/form', 'CommitteeController@create');
Route::get('/committee/add', 'CommitteeController@create');
Route::post('/committee/add', 'CommitteeController@store');

Route::get('/committee/members/{cid}', 'MembersController@index')->name('comm.assign');
Route::get('/committee/members/{cid}/ajax', 'MembersController@ajax')->name('comm.ajax');
Route::get('/committee/members/{cid}/add', 'MembersController@create')->name('members.add');
Route::post('/committee/members/{cid}/add', 'MembersController@store')->name('members.add');

Route::get('/list', 'ListController@index')->name('list');
Route::get('/charge-admin', 'ListController@getChargeMembership');
Route::get('/community-members-admin', 'ListController@getCommunityMembers');
Route::get('/rank-admin', 'ListController@getRank');

Route::get('/list/community/add', 'ListController@createCommunity');
Route::post('/list/community/add', 'ListController@storeCommunity')->name('community.add');