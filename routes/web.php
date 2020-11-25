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

// Route::get('/', function () {
//     return view('welcome');
// });
/*
Route::get('/', function () {
    return view('auth.login');
});
*/

Auth::routes();

Route::redirect('/home', '/');
Route::get('/', 'HomeController@index')->name('home');
Route::get('/committee', 'CommitteeController@index')->name('committee');
Route::get('/list', 'ListController@index')->name('list');

Route::get('/user-request', 'UserRequestController@index')->name('user-request');
