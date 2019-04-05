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
Route::get('/', 'AboutController@viewAbout')->name('view.about');
Route::get('/inventoryGuest', 'AboutController@getInventory')->name('view.inventoryGuest');
Route::get('/home', 'HomeController@viewHome')->name('home');
Route::get('/deviceCreation', 'HomeController@viewCreateDevice')->name('view.createDevice');
Route::get('/inventory', 'InventoryController@viewInventory')->name('view.inventory');
Route::get('/requestLoan/{deviceModel}', 'LoanController@viewLoanRequest')->name('view.requestLoan');
Route::get('/deviceDetails/{deviceModel}', 'HomeController@getDeviceDetails')->name('view.deviceDetails');
Route::get('/loansList', 'HomeController@viewLoansList')->name('view.loansList');
Route::get('/exportCSV', 'HomeController@viewReports')->name('view.reports');
Route::get('/checkLoan', 'LoanController@viewSearchLoan')->name('view.searchLoan');
Route::get('/edit/{deviceModel}', 'HomeController@viewEditDeviceDetails')->name('view.editDevice');

// Devices Routes
Route::get('/getSerialNumbers/{deviceModel}', 'HomeController@getSerialNumbers')->name('device.get.serialNumbers');
Route::post('/createDevice', 'DeviceController@createDevice')->name('device.create');
Route::post('/editDevice', 'DeviceController@editDevice')->name('device.edit');
Route::get('/getDeviceNames', 'DeviceController@getDeviceNames')->name('device.get.names');
Route::get('/getDeviceBrands', 'DeviceController@getDeviceBrands')->name('device.get.brands');
Route::get('/getDeviceModels', 'DeviceController@getDeviceModels')->name('device.get.models');

// Loans Controller
Route::get('/getAllLoans', 'HomeController@getAllLoans')->name('loan.get.all');
Route::post('/createLoan', 'LoanController@createLoan')->name('loan.create');
Route::post('/changeStatus', 'LoanController@setLoanStatus')->name('loan.set.status');
Route::post('/cancelLoan', 'LoanController@cancelLoan')->name('loan.cancel');
Route::get('/exportLoans', 'LoanController@getLoansToCSV')->name('report.get.loans');

// About Controller | Public Controller
Route::post('/searchLoan', 'LoanController@getLoanFromID')->name('loan.get.byID');
