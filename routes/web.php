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

Auth::routes([
 		'register' => false,
		'reset' => false,
		'verify' => false,
]);

/*** Home Page ***/
Route::redirect('home', '/');
Route::get('/', 'HomeController@index')->name('home');
Route::post('/committee/list', 'HomeController@getCommittees')->name('committee.list');
Route::post('/committee/search', 'HomeController@committeeSearch')->name('committee.search');

/*** Committee Member Name Search ***/
Route::post('/member/list', 'HomeController@getMembers')->name('member.list');
Route::post('/member/search', 'HomeController@memberSearch')->name('member.search');

/*** Committee Pages ***/
Route::get('/committee', 'CommitteeController@index')->name('committee');
Route::get('/committee/admin', 'CommitteeController@displayCommitteeAssignments')->name('committee.admin');;
Route::get('/committee/form', 'CommitteeController@create');
Route::get('/committee/add', 'CommitteeController@create');
Route::post('/committee/add', 'CommitteeController@store')->name('committee.add');
Route::post('/committee/update', 'CommitteeController@update')->name('committee.update');
Route::post('/committee/destroy', 'CommitteeController@destroy')->name('committee.destroy');

Route::get('/committee/members/{cid}', 'MembersController@index')->name('comm.assign');
Route::get('/committee/members/{cid}/memberships', 'MembersController@getMemberships')->name('members.table');
Route::get('/committee/members/{cid}/add', 'MembersController@create')->name('members.add');
Route::post('/committee/members/{cid}/add', 'MembersController@store')->name('members.add');
Route::get('/committee/members/{cid}/edit/{mid}', 'MembersController@create')->name('members.edit');
Route::post('/committee/members/update/{mid}', 'MembersController@update')->name('members.update');
Route::post('/committee/members/destroy/{mid}', 'MembersController@destroy')->name('members.destroy');

/*** Employee Name Search ***/
Route::post('/employees/search', 'MembersController@getEmployees')->name('employees.search');
// Route::post('/employees/search', 'Controller@getEmployees')->name('employees.search');

/*** Charge Membership Pages ***/
Route::get('/charge', 'ChargeController@index')->name('charge');
Route::get('/charge/admin', 'ChargeController@getCommitteeChargeCount')->name('charge.admin');
Route::get('/charge/assignments/{id}', 'ChargeController@getMembership')->name('charge.assignments');
Route::get('/charge/assignments/{id}/ajax', 'ChargeController@getChargeMemberships')->name('charge.assignments.ajax');
Route::get('/charge/assignments/{id}/charges/ajax', 'ChargeController@getCharges')->name('charges.list.ajax');
Route::post('/charge/assignments/add', 'ChargeController@store')->name('charge.assignments.add');
Route::post('/charge/assignments/update', 'ChargeController@update')->name('charge.assignments.update');
Route::post('/charge/assignments/destroy', 'ChargeController@destroy')->name('charge.assignments.destroy');

/*** List Pages ***/
Route::get('/list', 'ListController@index')->name('list');
Route::get('/list/charge/admin', 'ListController@getCharges')->name('list.charge.admin');
Route::get('/list/community/admin', 'ListController@getCommunityMembers')->name('list.community.admin');
Route::get('/list/rank/admin', 'ListController@getRank')->name('list.rank.admin');

Route::get('/list/community/add', 'ListController@createCommunity');
Route::post('/list/community/add', 'ListController@storeCommunity')->name('community.add');
Route::post('/list/community/update', 'ListController@updateCommunity')->name('community.update');
Route::post('/list/community/destroy', 'ListController@destroyCommunity')->name('community.destroy');

Route::post('/list/charge/add', 'ListController@storeCharge')->name('list.charge.add');
Route::post('/list/charge/update', 'ListController@updateCharge')->name('charge.update');
Route::post('/list/charge/destroy', 'ListController@destroyCharge')->name('charge.destroy');

Route::post('/list/rank/add', 'ListController@storeRank')->name('rank.add');
Route::post('/list/rank/update', 'ListController@updateRank')->name('rank.update');
Route::post('/list/rank/destroy', 'ListController@destroyRank')->name('rank.destroy');
