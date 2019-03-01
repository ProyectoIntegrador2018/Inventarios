<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Device;

use DB;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home');
    }

    public function deviceCreation(){
        return view('device-creation');
    }

    public function inventory(){
        
        $devices = DB::select("
            SELECT COUNT(d.id) as quantity, d.name, d.brand, d.model
            FROM devices d JOIN states s
            ON d.id = s.device_id
            WHERE s.state = 'Available'
            GROUP BY d.name, d.brand, d.model
        ");

        $quantity = count($devices);

        return view('inventory')->with('devices', $devices)->with('quantity', $quantity);
    }

    public function requestLoan($requestedDevice){
        
        $serialNumbers = DB::select("
            SELECT d.serial_number
            FROM devices d JOIN states s
            ON d.id = s.device_id
            WHERE d.model = '$requestedDevice' AND s.state = 'Available';
        ");

        $modelInformation = DB::select("
            SELECT d.name, d.brand, d.model
            FROM devices d JOIN states s
            ON d.id = s.device_id
            WHERE d.model = '$requestedDevice' AND s.state = 'Available'
            GROUP BY d.name, d.brand, d.model;
        ");

        $modelInformation = $modelInformation[0];

        $quantity = count($serialNumbers);

        return view('request-loan')->with('serialNumbers', $serialNumbers)->with('modelInformation', $modelInformation)->with('quantity', $quantity);
    }

    public function getLoans() {
        $loans = DB::select("
        SELECT *
        FROM loans
    ");
    }
}
