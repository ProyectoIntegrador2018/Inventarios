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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

// Navigation Routes
Route::get('/home', 'HomeController@index')->name('home');
Route::get('/deviceCreation', 'HomeController@deviceCreation')->name('deviceCreation');
Route::get('/inventory', 'HomeController@inventory')->name('inventory');
Route::get('/requestLoan/{deviceModel}', 'HomeController@requestLoan')->name('requestLoan');

// Devices Routes
Route::post('/createDevice', 'DeviceController@createDevice')->name('createDevice');

// Loans Controller
Route::post('/createLoan', 'LoanController@createLoan')->name('createLoan');