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

    /*
    public function inventory(){

        // $id = Auth::user()->id;
        $id = "1";
        
        $devices = DB::select("
            SELECT COUNT(d.id) as quantity, d.name, d.brand, d.model, l.building, l.room, u.id
            FROM states s
            JOIN devices d ON d.id = s.device_id
            JOIN locations l ON l.id = d.location_id
            JOIN users u ON u.id = l.user_id
            WHERE s.state = 'Available' AND u.id = '$id'
            GROUP BY d.name, d.brand, d.model, l.building, l.room, u.id;
        ");

        $quantity = count($devices);

        return view('inventory')->with('devices', $devices)->with('quantity', $quantity);
    }
    */

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
        WHERE model = '$requestedDevice' AND s.state = 'Exclusive';
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

    public function edit($requestedDevice){
        $serialNumbers = DB::select("
            SELECT d.serial_number, s.state
            FROM devices d JOIN states s
            ON d.id = s.device_id
            WHERE d.model = '$requestedDevice';
        ");

        $modelInformation = DB::select("
        SELECT d.name, d.brand, d.model, d.location_id
        FROM devices d
        WHERE d.model = '$requestedDevice';
        ");
        $modelInformation = $modelInformation[0];

        $deviceLocation = DB::select("
        SELECT building, room
        FROM locations l
        WHERE l.id = '$modelInformation->location_id'
        ");
        $location = $deviceLocation[0];

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
        WHERE model = '$requestedDevice' AND s.state = 'Exclusive';
        ");
        $exclusiveDevices = $exclusiveDevices[0]->quantity;

        $tagList = DB::select("
        SELECT t.tag
        FROM tags t JOIN device_tag dt
        ON t.id = dt.tag_id
        JOIN devices d
        ON d.id = dt.device_id
        WHERE model = '$requestedDevice'
        GROUP BY t.tag;
        ");

        $tags = $tagList[0]->tag;
        for($i = 1; $i < sizeof($tagList); $i++) {
            $tags = $tags.', '.$tagList[$i]->tag;
        }

        $totalDevices = $availableDevices + $reservedDevices + $repairingDevices + $decreaseDevices + $exclusiveDevices;

        return view('edit')->with('device', $modelInformation)
        ->with('availableDevices', $availableDevices)
        ->with('reservedDevices', $reservedDevices)
        ->with('repairingDevices', $repairingDevices)
        ->with('decreaseDevices', $decreaseDevices)
        ->with('exclusiveDevices', $exclusiveDevices)
        ->with('serialNumbers', $serialNumbers)
        ->with('totalDevices', $totalDevices)
        ->with('location', $location)
        ->with('tags', $tags)
        ;
    }

    public function getLoans(){
        return view('loans-list');
    }

    public function getAllLoans() {
        /*
        txt_solicitante_nombre - LISTO
        txt_solicitante_degree - LISTO
        txt_solicitante_email - LISTO
        txt_solicitante_id - LISTO

        txt_responsable_nombre - LISTO
        txt_responsable_email - LISTO

        txt_dispositivo_nombre - LISTO
        bdg_dispositivo_status - LISTO
        txt_dispositivo_cantidad - LISTO
        txt_dispositivo_serie - LISTO

        txt_dispositivo_inicio
        txt_dispositivo_fin
        txt_dispositivo_motivo
        */
        $loans = DB::select("
        SELECT loans.id,
        loans.status,
        responsables.name AS responsableName,
        responsables.email AS responsableEmail,
        applicants.name AS solicitantName,
        devices.name AS deviceName,
        devices.serial_number AS deviceSerialNumber,
        COUNT(devices.id) AS deviceQuantity,
        applicants.degree AS solicitantDegree,
        applicants.email AS solicitantEmail,
        applicants.applicant_id AS solicitantID,
        states.state AS deviceState,
        loans.start_date AS loanStartDate,
        loans.end_date AS loanEndDate,
        loans.reason AS loanReason
        FROM responsables
        JOIN applicants ON responsables.applicant_id = applicants.id
        JOIN loans ON applicants.id = loans.applicant_id
        JOIN loan_device ON loans.id = loan_device.loan_id
        JOIN devices ON loan_device.device_id = devices.id
        JOIN states ON devices.id = states.device_id
        GROUP BY responsables.name, applicants.name, loans.id, loans.status, devices.name, applicants.degree, applicants.email, applicants.applicant_id, responsables.email, devices.serial_number, states.state, loans.start_date, loans.end_date, loans.reason
        ORDER BY loans.id ASC;
        ");

        return json_encode($loans);
    }

    public function getSerialNumbers($model){
        $serialNumbers = DB::select("
            SELECT d.serial_number, s.state
            FROM devices d JOIN states s
            ON d.id = s.device_id
            WHERE d.model = '$model';
        ");
        return json_encode($serialNumbers);
    }
}
