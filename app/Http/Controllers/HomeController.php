<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

use App\Device;
use App\Loan;

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

    public function exportCSV(){
        return view('export-csv');
    }

    public function inventory(){

        $id = Auth::user()->id;
        
        $devices = DB::select("
            SELECT COUNT(d.id) as quantity, d.name, d.brand, d.model, l.building, l.room, u.id
            FROM states s
            JOIN devices d ON s.device_id = d.id
            JOIN locations l ON l.id = d.location_id
            JOIN users u ON u.id = l.user_id
            WHERE s.state = 'Available' AND u.id = '$id'
            GROUP BY d.name, d.brand, d.model, l.building, l.room, u.id;
        ");

        /*
        $devices = DB::select("
            SELECT COUNT(d.id) as quantity, d.name, d.brand, d.model
            FROM devices d JOIN states s
            ON d.id = s.device_id
            WHERE s.state = 'Available'
            GROUP BY d.name, d.brand, d.model
        ");
        */

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

    public function deviceDetails($requestedDevice){

        $serialNumbers = DB::select("
            SELECT d.serial_number
            FROM devices d
            WHERE d.model = '$requestedDevice';
        ");

        $modelInformation = DB::select("
        SELECT d.name, d.brand, d.model
        FROM devices d
        WHERE d.model = '$requestedDevice';
        ");
        $modelInformation = $modelInformation[0];

        $availableDevices = DB::select("
        SELECT count(d.id) as quantity
        FROM devices d JOIN states s
        ON d.id = s.device_id
        WHERE model = '$requestedDevice' AND s.state = 'Available';
        ");
        $availableDevices = $availableDevices[0]->quantity;

        $reservedDevices = DB::select("
        SELECT count(d.id) as quantity
        FROM devices d JOIN states s
        ON d.id = s.device_id
        WHERE model = '$requestedDevice' AND s.state = 'Reserved';
        ");
        $reservedDevices = $reservedDevices[0]->quantity;

        $repairingDevices = DB::select("
        SELECT count(d.id) as quantity
        FROM devices d JOIN states s
        ON d.id = s.device_id
        WHERE model = '$requestedDevice' AND s.state = 'Repairing';
        ");
        $repairingDevices = $repairingDevices[0]->quantity;

        $decreaseDevices = DB::select("
        SELECT count(d.id) as quantity
        FROM devices d JOIN states s
        ON d.id = s.device_id
        WHERE model = '$requestedDevice' AND s.state = 'Decrease';
        ");
        $decreaseDevices = $decreaseDevices[0]->quantity;

        $exclusiveDevices = DB::select("
        SELECT count(d.id) as quantity
        FROM devices d JOIN states s
        ON d.id = s.device_id
        WHERE model = '$requestedDevice' AND s.state = 'Decrease';
        ");
        $exclusiveDevices = $exclusiveDevices[0]->quantity;

        $totalDevices = $availableDevices + $reservedDevices + $repairingDevices + $decreaseDevices + $exclusiveDevices;

        return view('device-details')->with('device', $modelInformation)
        ->with('availableDevices', $availableDevices)
        ->with('reservedDevices', $reservedDevices)
        ->with('repairingDevices', $repairingDevices)
        ->with('decreaseDevices', $decreaseDevices)
        ->with('exclusiveDevices', $exclusiveDevices)
        ->with('serialNumbers', $serialNumbers)
        ->with('totalDevices', $totalDevices)
        ;
    }

    public function getLoans(){
        return view('loans-list');
    }

    public function getAllLoans() {
        
        $loans = DB::select("
        SELECT loans.id, loans.status, responsables.name AS responsableName, applicants.name AS solicitantName, devices.name AS deviceName, COUNT(devices.id) AS deviceQuantity
        FROM responsables
        JOIN applicants ON responsables.applicant_id = applicants.id
        JOIN loans ON applicants.id = loans.applicant_id
        JOIN loan_device ON loans.id = loan_device.loan_id
        JOIN devices ON loan_device.device_id = devices.id
        GROUP BY responsables.name, applicants.name, loans.id, loans.status, devices.name
        ORDER BY loans.id ASC;
        ");

        return json_encode($loans);
    }
}
