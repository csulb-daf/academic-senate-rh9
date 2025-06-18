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

use App\Http\Controllers\ChargeController;
use App\Http\Controllers\CommitteeController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ListController;
use App\Http\Controllers\MembersController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// use Illuminate\Support\Facades\View;

Auth::routes([
    'register' => false,
    'reset' => false,
    'verify' => false,
]);

/*** Home Page ***/
Route::redirect('home', '/');
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::post('/committee/list', [HomeController::class, 'getCommittees'])->name('committee.list');
Route::post('/committee/search', [HomeController::class, 'committeeSearch'])->name('committee.search');

/*** Committee Member Name Search ***/
Route::post('/member/list', [HomeController::class, 'getMembers'])->name('member.list');
Route::post('/member/search', [HomeController::class, 'memberSearch'])->name('member.search');

/*** Committee Pages ***/
Route::prefix('committee')->group(function () {
    Route::get('/', [CommitteeController::class, 'index'])->name('committee');
    Route::get('admin', [CommitteeController::class, 'displayCommitteeAssignments'])->name('committee.admin');
    Route::get('form', [CommitteeController::class, 'create']);
    Route::get('add', [CommitteeController::class, 'create']);
    Route::post('add', [CommitteeController::class, 'store'])->name('committee.add');
    Route::post('update', [CommitteeController::class, 'update'])->name('committee.update');
    Route::post('destroy', [CommitteeController::class, 'destroy'])->name('committee.destroy');
    Route::get('members/{cid}', [MembersController::class, 'index'])->name('comm.assign');
    Route::get('members/{cid}/memberships', [MembersController::class, 'getMemberships'])->name('members.table');
    Route::get('members/{cid}/add/{mid}/{chid}', [MembersController::class, 'create'])->name('members.add.view');
    Route::post('members/{cid}/add', [MembersController::class, 'store'])->name('members.add');
    Route::get('members/{cid}/edit/{mid}', [MembersController::class, 'create'])->name('members.edit');
    Route::post('members/update/{mid}', [MembersController::class, 'update'])->name('members.update');
    Route::post('members/destroy/{mid}', [MembersController::class, 'destroy'])->name('members.destroy');
});

/*** Employee Name Search ***/
Route::post('/employees/search', [MembersController::class, 'getEmployees'])->name('employees.search');

/*** Charge Membership Pages ***/
Route::get('/charge', [ChargeController::class, 'index'])->name('charge');
Route::get('/charge/admin', [ChargeController::class, 'getCommitteeChargeCount'])->name('charge.admin');
Route::get('/charge/assignments/{id}', [ChargeController::class, 'getMembership'])->name('charge.assignments');
Route::get('/charge/assignments/{id}/ajax', [ChargeController::class, 'getChargeMemberships'])->name('charge.assignments.ajax');
Route::get('/charge/assignments/{id}/charges/ajax', [ChargeController::class, 'getCharges'])->name('charges.list.ajax');
Route::post('/charge/assignments/add', [ChargeController::class, 'store'])->name('charge.assignments.add');
Route::post('/charge/assignments/update', [ChargeController::class, 'update'])->name('charge.assignments.update');
Route::post('/charge/assignments/destroy', [ChargeController::class, 'destroy'])->name('charge.assignments.destroy');

/*** List Pages ***/
Route::get('/list', [ListController::class, 'index'])->name('list');
Route::get('/list/charge/admin', [ListController::class, 'getCharges'])->name('list.charge.admin');
Route::get('/list/community/admin', [ListController::class, 'getCommunityMembers'])->name('list.community.admin');
Route::get('/list/rank/admin', [ListController::class, 'getRank'])->name('list.rank.admin');

Route::get('/list/community/add', [ListController::class, 'createCommunity']);
Route::post('/list/community/add', [ListController::class, 'storeCommunity'])->name('community.add');
Route::post('/list/community/update', [ListController::class, 'updateCommunity'])->name('community.update');
Route::post('/list/community/destroy', [ListController::class, 'destroyCommunity'])->name('community.destroy');

Route::post('/list/charge/add', [ListController::class, 'storeCharge'])->name('list.charge.add');
Route::post('/list/charge/update', [ListController::class, 'updateCharge'])->name('charge.update');
Route::post('/list/charge/destroy', [ListController::class, 'destroyCharge'])->name('charge.destroy');

Route::post('/list/rank/add', [ListController::class, 'storeRank'])->name('rank.add');
Route::post('/list/rank/update', [ListController::class, 'updateRank'])->name('rank.update');
Route::post('/list/rank/destroy', [ListController::class, 'destroyRank'])->name('rank.destroy');
