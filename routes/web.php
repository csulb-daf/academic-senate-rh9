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

/*** Committee Pages ***/
Route::get('/committee', 'CommitteeController@index')->name('committee');
Route::get('/comm-admin', 'CommitteeController@getComms');
Route::get('/committee/form', 'CommitteeController@create');
Route::get('/committee/add', 'CommitteeController@create');
Route::post('/committee/add', 'CommitteeController@store');

Route::get('/committee/members/{cid}', 'MembersController@index')->name('comm.assign');
Route::get('/committee/members/{cid}/ajax', 'MembersController@ajax')->name('comm.ajax');
Route::get('/committee/members/{cid}/add', 'MembersController@create')->name('members.add');
Route::post('/committee/members/{cid}/add', 'MembersController@store')->name('members.add');

/*** Charge Pages ***/
Route::get('/charge', 'ChargeController@index')->name('charge');
Route::get('/charge/admin', 'ChargeController@getChargeMemberships')->name('charge.admin');
// Route::get('/charge/form', 'ChargeController@create');
// Route::get('/charge/add', 'ChargeController@create');
// Route::post('/charge/add', 'ChargeController@store');

Route::get('/charge/membership/{cid}', 'ChargeController@indexMembership')->name('charge.assign');

/*** List Pages ***/
Route::get('/list', 'ListController@index')->name('list');
Route::get('/charge-admin', 'ListController@getChargeMembership');
Route::get('/community-members-admin', 'ListController@getCommunityMembers');
Route::get('/rank-admin', 'ListController@getRank');

Route::get('/list/community/add', 'ListController@createCommunity');
Route::post('/list/community/add', 'ListController@storeCommunity')->name('community.add');
Route::post('/list/community/update', 'ListController@updateCommunity')->name('community.update');
Route::post('/list/community/destroy', 'ListController@destroyCommunity')->name('community.destroy');

Route::get('/list/charge/add', 'ListController@createCharge')->name('charge.add');
Route::post('/list/charge/add', 'ListController@storeCharge')->name('charge.add');
Route::post('/list/charge/update', 'ListController@updateCharge')->name('charge.update');
Route::post('/list/charge/destroy', 'ListController@destroyCharge')->name('charge.destroy');

Route::post('/list/rank/add', 'ListController@storeRank')->name('rank.add');
Route::post('/list/rank/update', 'ListController@updateRank')->name('rank.update');
Route::post('/list/rank/destroy', 'ListController@destroyRank')->name('rank.destroy');