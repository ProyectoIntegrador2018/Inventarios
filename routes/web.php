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
Route::get('/home', 'HomeController@viewHome')->name('home');
Route::get('/deviceCreation', 'HomeController@viewCreateDevice')->name('deviceCreation');
Route::get('/inventory', 'InventoryController@viewInventory')->name('inventory');
Route::get('/requestLoan/{deviceModel}', 'LoanController@viewLoanRequest')->name('requestLoan');
Route::get('/loans', 'HomeController@viewLoansList')->name('loans');
Route::get('/deviceDetails/{deviceModel}', 'HomeController@getDeviceDetails')->name('deviceDetails');
Route::get('/loansList', 'HomeController@viewLoansList')->name('loansList');
Route::get('/getAllLoans', 'HomeController@getAllLoans')->name('getAllLoans');
Route::get('/exportCSV', 'HomeController@viewReports')->name('exportCSV');
Route::get('/checkLoan', 'LoanController@viewSearchLoan')->name('checkLoan');
Route::get('/edit/{deviceModel}', 'HomeController@viewEditDeviceDetails')->name('edit');
Route::get('/getSerialNumbers/{deviceModel}', 'HomeController@getSerialNumbers')->name('getSerialNumbers');

// Devices Routes
Route::post('/createDevice', 'DeviceController@createDevice')->name('createDevice');
Route::post('/editDevice', 'DeviceController@editDevice')->name('editDevice');
Route::get('/getDeviceNames', 'DeviceController@getDeviceNames')->name('getDeviceNames');
Route::get('/getDeviceBrands', 'DeviceController@getDeviceBrands')->name('getDeviceBrands');
Route::get('/getDeviceModels', 'DeviceController@getDeviceModels')->name('getDeviceModels');

// Loans Controller
Route::post('/createLoan', 'LoanController@createLoan')->name('createLoan');
Route::post('/changeStatus', 'LoanController@setLoanStatus')->name('changeStatus');
Route::post('/cancelLoan', 'LoanController@cancelLoan')->name('cancelLoan');
Route::get('/exportLoans', 'LoanController@getLoansToCSV')->name('exportLoans');

// About Controller | Public Controller
Route::get('/', 'AboutController@viewAbout')->name('about');
Route::post('/searchLoan', 'LoanController@getLoanFromID')->name('loan.search');
Route::get('/inventoryGuest', 'AboutController@getInventory')->name('inventoryGuest');
