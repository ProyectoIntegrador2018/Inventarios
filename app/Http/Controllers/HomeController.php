<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

use App\Device;
use App\Loan;

use DB;

class HomeController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function viewHome()
    {
        return view('home');
    }

    public function viewCreateDevice()
    {
        return view('device-creation');
    }

    public function viewReports()
    {
        return view('export-csv');
    }

    public function viewEditDeviceDetails($requestedDevice)
    {
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

    public function viewLoansList()
    {
      return view('loans-list');
    }

    public function getDeviceDetails($requestedDevice)
    {

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

    public function getAllLoans()
    {
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
      FROM loans
      JOIN applicants ON loans.applicant_id = applicants.id
      JOIN loan_device ON loans.id = loan_device.loan_id
      JOIN devices ON loan_device.device_id = devices.id
	    JOIN states ON devices.id = states.device_id
      LEFT OUTER JOIN responsables ON applicants.id = responsables.applicant_id
      GROUP BY responsables.name, applicants.name, loans.id, loans.status, devices.name, applicants.degree, applicants.email, applicants.applicant_id, responsables.email, devices.serial_number, states.state, loans.start_date, loans.end_date, loans.reason
      ORDER BY loans.id ASC;
      ");

      // FROM responsables
      // JOIN applicants ON responsables.applicant_id = applicants.id
      // JOIN loans ON applicants.id = loans.applicant_id
      // JOIN loan_device ON loans.id = loan_device.loan_id
      // JOIN devices ON loan_device.device_id = devices.id
      // JOIN states ON devices.id = states.device_id

      return json_encode($loans);
    }


    public function getSerialNumbers($model)
    {
      $serialNumbers = DB::select("
      SELECT d.serial_number, s.state
      FROM devices d JOIN states s
      ON d.id = s.device_id
      WHERE d.model = '$model';
      ");
      return json_encode($serialNumbers);
    }

    public function requestLoan($requestedDevice)
    {

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

}
