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

// Navigation Routes
Route::get('/home', 'HomeController@index')->name('home');
Route::get('/deviceCreation', 'HomeController@deviceCreation')->name('deviceCreation');
Route::get('/inventory', 'InventoryController@inventory')->name('inventory');
Route::get('/requestLoan/{deviceModel}', 'LoanController@showView_LoanRequest')->name('requestLoan');
Route::get('/loans', 'HomeController@getLoans')->name('loans');
Route::get('/deviceDetails/{deviceModel}', 'HomeController@deviceDetails')->name('deviceDetails');
Route::get('/loansList', 'HomeController@getLoans')->name('loansList');
Route::get('/getAllLoans', 'HomeController@getAllLoans')->name('getAllLoans');
Route::get('/exportCSV', 'HomeController@exportCSV')->name('exportCSV');
Route::get('/checkLoan', 'LoanController@checkLoan')->name('checkLoan');

// Devices Routes
Route::post('/createDevice', 'DeviceController@createDevice')->name('createDevice');
Route::get('/getDeviceNames', 'DeviceController@getDeviceNames')->name('getDeviceNames');
Route::get('/getDeviceBrands', 'DeviceController@getDeviceBrands')->name('getDeviceBrands');
Route::get('/getDeviceModels', 'DeviceController@getDeviceModels')->name('getDeviceModels');

// Loans Controller
Route::post('/createLoan', 'LoanController@createLoan')->name('createLoan');
Route::post('/changeStatus', 'LoanController@changeStatus')->name('changeStatus');
Route::post('/cancelLoan', 'LoanController@cancelLoan')->name('cancelLoan');

Route::get('/exportLoans', 'LoanController@exportLoans')->name('exportLoans');

// About Controller | Public Controller
// Route::get('/about', 'AboutController@about')->name('about');
Route::get('/', 'AboutController@about')->name('about');
Route::post('/searchLoan', 'LoanController@searchLoan')->name('loan.search');
