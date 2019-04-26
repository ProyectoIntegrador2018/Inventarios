<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Device;
use App\Loan;
use App\State;

use DB;

use Carbon\Carbon;

use App\Exports\LoansExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Controllers\Controller;

class LoanController extends Controller
{
    public function viewSearchLoan()
    {
      return view('show-loan');
    }

    public function viewLoanRequest ($requestedDevice)
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

    public function setLoanStatus(Request $request)
    {

      $loanID     = $request->input('loanID');
      $loanStatus = $request->input('loanStatus');

      /*
      -> Nueva solicitud
      <- Apartado

      -> Cancelado
      <-

      -> Apartado
      <- Prestado

      -> Prestado
      <- Recibido

      -> Recibido
      <-

      -> Recibido tarde
      <-

      -> Expirado
      <- Recibido tarde

      -> Unknown status
      <-
      */

      /*
      -> New
      <- Separated

      -> Cancelled
      <-

      -> Separated
      <- Taken

      -> Taken
      <- Received

      -> Received
      <-

      -> Received late
      <-

      -> Expired
      <- Received late

      -> Unknown status
      <-
      */

      switch ($loanStatus)
      {
        case "New":
        //$loanStatus = "Separated";
        Loan::where('id', $loanID)->update(['status' => "Separated"]);
        break;
        case "Cancelled":
        break;
        case "Separated":
        // $loanStatus = "Taken";
        Loan::where('id', $loanID)->update(['status' => "Taken"]);
        break;
        case "Taken":
        // $loanStatus = "Received";
        Loan::where('id', $loanID)->update(['status' => "Received"]);
          $device_ids = DB::select("
            SELECT d.id
            FROM loans l JOIN loan_device ld ON l.id = ld.loan_id
            JOIN devices d ON d.id = ld.device_id
            JOIN states s ON s.device_id = d.id
            WHERE l.id = '$loanID';
          ");

          foreach ($device_ids as $device_id) {
            $newStatus = "Available";
            State::where('device_id', $device_id->id)
              ->update(['state' => $newStatus]);
          }
        break;
        case "Received":
        break;
        case "Received late":
        break;
        case "Expired":
        // $loanStatus = "Received late";
        Loan::where('id', $loanID)->update(['status' => "Received late"]);
        break;
        case "Unknown status":
        break;
      }

      $response["status"] = 1;

      $response["message"] = "Loan status successfully updated";

      return json_encode($response);

    }

    public function getLoansToCSV(Request $request)
    {
        // Translate from JSON plain text true, to PHP boolean true
        $request["allDates"] = $this->transformToBoolean($request["allDates"]);
        $request["professor"] = $this->transformToBoolean($request["professor"]);
        $request["student"] = $this->transformToBoolean($request["student"]);
        $request["allStatus"] = $this->transformToBoolean($request["allStatus"]);

        // Recapture the data received from the VIEW
        $dates       = array( 'selectAll' => $request["allDates"],
                              'start'     => $request["startDate"],
                              'end'       => $request["endDate"]);
        $solicitants = array( 'professor' => $request["professor"],
                              'student'   => $request["student"]);
        $status      = array( 'selectAll'  => $request["allStatus"],
                              'statuses'   => $request["statuses"]);
        $inputs      = array( 'dates'        => $dates,
                              'solicitants'  => $solicitants,
                              'status'       => $status);

        // Assign a descriptive name to the spread sheet file
        $fileName = 'Reporte-prestamos';
        if($request["allDates"])
        {
          $fileName = "{$fileName}_Historico";
        }
        else
        {
          // Replace '/' character in dates to avoid file-naming-error
          $startDate = str_replace('/', "-", $request["startDate"]);
          $endDate = str_replace('/', "-", $request["endDate"]);
          $fileName = "{$fileName}_del_{$startDate}_al_{$endDate}";
        }
        $fileName = "{$fileName}.xlsx";

        // Return the Excel file with the report
        return Excel::download(new LoansExport($inputs), $fileName);
    }

    public function getDeviceNameFromLoan($loanID)
    {
      $deviceNames = DB::select("
          SELECT d.name
          FROM devices d
          GROUP BY d.name;
      ");
      return array($deviceNames);
    }

    public function getLoanFromID(Request $request)
    {
      // Retrieve the loan ID from the search bar
      $loanID = $request->input('txb_search');

      // Search loan with the given ID and retrieve the loan details
      $loan = DB::select("
          SELECT l.start_date, l.end_date, l.status
          FROM loans l
          WHERE l.id = '$loanID';
      ");

      // If the loan exists, keep searching for more details
      if(sizeof($loan) > 0)
      {
        // Search its applicant details
        $applicant = DB::select("
        SELECT a.name, a.applicant_id, a.degree
        FROM loans l JOIN applicants a
        ON l.applicant_id = a.id
        WHERE l.id = '$loanID';
        ");
        // Search the details of a responsible, if exists.
        $responsable = DB::select("
        SELECT r.name
        FROM loans l JOIN applicants a
        ON l.applicant_id = a.id
        JOIN responsables r
        ON a.id = r.applicant_id
        WHERE l.id = '$loanID';
        ");
        // Search the details of the device loaned
        $device = DB::select("
          SELECT d.name, d.brand, d.model
          FROM loans l JOIN loan_device ld
            ON l.id = ld.loan_id
          JOIN devices d
            ON ld.device_id = d.id
          WHERE l.id = '$loanID';
        ");
        // Prepare the answer
        $response = array(
          'status' =>      'SUCCESS',
          'device' =>      $device[0],
          'loan' =>      $loan[0],
          'applicant' =>   $applicant[0],
          'responsable' => $responsable[0]);


      }
      // If the loan doesnt exists, then return.
      else
      {
        $response = array( 'status' => 'NOT FOUND' );
      }
      return json_encode($response);
    }

    public function cancelLoan(Request $request)
    {

      $loanID     = $request->input('loanID');
      $loanStatus = $request->input('loanStatus');

      Loan::where('id', $loanID)->update(['status' => "Cancelled"]);
      
      $device_ids = DB::select("
        SELECT d.id
        FROM loans l JOIN loan_device ld ON l.id = ld.loan_id
        JOIN devices d ON d.id = ld.device_id
        JOIN states s ON s.device_id = d.id
        WHERE l.id = '$loanID';
      ");

      foreach ($device_ids as $device_id) {
        $newStatus = "Available";
        State::where('device_id', $device_id->id)
          ->update(['state' => $newStatus]);
      }
      
      $response["status"] = 1;
      $response["message"] = "Loan successfully cancelled";
      return json_encode($response);
    }

    public function createLoan(Request $request)
    {
      $model            = $request->input('model');

      $quantity         = $request->input('quantity');

      $reason           = $request->input('reason');
      $dates            = $request->input('dates');

      $applicant        = $request->input('applicant');
      $applicantID      = $request->input('applicantID');
      $email            = $request->input('email');
      $bachelor         = $request->input('bachelor');

      $responsableName  = $request->input('responsableName');
      $responsableEmail = $request->input('responsableEmail');

      $isStudent = $request->input('isStudent');

      // echo $model . "-" . $quantity . "-" . $reason . "-" . $dates . "-" . $applicant . "-" . $applicantID . "-" . $email . "-" . $bachelor . "-" . $responsableName . "-" .$responsableEmail;

      // Values needed to create a loan:
      // Verify before that there are enough devices available from that model
      /*
      Order of instances creation:
      1º - Applicant
      ---->name
      ---->email
      ---->applicant_id
      ---->degree
      2º - Loan
      ---->start_date
      ---->end_date
      ---->loan_date
      ---->return_date
      ---->status
      ---->reason
      ---->applicant_id
      3º - Responsable (In the case of existing)
      ---->name
      ---->email
      ---->responsable_id
      ---->applicant_id
      4º - Loan-Device
      loan_id
      device_id
      */


      $modelAvailability = DB::select("
      SELECT COUNT(d.model) as quantity
      FROM devices d JOIN states s
      ON d.id = s.device_id
      WHERE d.model = '$model' AND s.state = 'Available'
      GROUP BY d.model;
      ");

      $modelAvailability = $modelAvailability[0]->quantity;

      if($modelAvailability > 0)
      {
        // There is at least one available
        if($quantity <= $modelAvailability)
        {
          // There are enough devices to create the loan

          // Applicant creation
          // Responsable creation
          // Loan creation
          // Loan - Device
          // State of the device (To 'Reserved')
          DB::table('applicants')->insert(
          [
          'name'         => $applicant,
          'email'        => $email,
          'applicant_id' => $applicantID,
          'degree'       => $bachelor,
          'created_at'   => Carbon::now(),
          'updated_at'   => Carbon::now()
          ]
          );

          $lastApplicantID = DB::table('applicants')->orderBy('id', 'desc')->first();
          $lastApplicantID = $lastApplicantID->id;

          if($isStudent == true)
          {
            // responsable_id is going to be null at the moment
            DB::table('responsables')->insert(
            [
            'name'           => $responsableName,
            'email'          => $responsableEmail,
            // 'responsable_id' => "",
            'applicant_id'   => $lastApplicantID,
            'created_at'     => Carbon::now(),
            'updated_at'     => Carbon::now()
            ]
            );
          }

          $dates = explode("-", $dates);

          DB::table('loans')->insert(
          [
          'start_date'   => Carbon::parse($dates[0]),
          'end_date'     => Carbon::parse($dates[1]),
          'loan_date'    => Carbon::now(),
          'return_date'  => Carbon::parse($dates[1]),
          'status'       => 'New',
          'reason'       => $reason,
          'applicant_id' => $lastApplicantID,
          'created_at'   => Carbon::now(),
          'updated_at'   => Carbon::now()
          ]
          );

          $lastLoanID = DB::table('loans')->orderBy('id', 'desc')->first();

          // Obtener los n elementos dependiendo de la cantidad y el modelo
          // Crear las relaciones Loan - Device
          // Cambiar los estados de los dispositivos a 'Reserved'

          $devicesToReserve = DB::select("
          SELECT d.*
          FROM devices d JOIN states s
          ON d.id = s.device_id
          WHERE d.model = '$model' AND s.state = 'Available'
          LIMIT '$quantity';
          ");

          for($x = 0; $x < $quantity; $x++)
          {

            DB::table('loan_device')->insert(
            [
            'loan_id'        => $lastLoanID->id,
            'device_id'      => $devicesToReserve[$x]->id,
            'created_at'     => Carbon::now(),
            'updated_at'     => Carbon::now()
            ]
            );

            $lastLoanDeviceID = DB::table('loan_device')->orderBy('id', 'desc')->first();

            State::where('device_id', $lastLoanDeviceID->device_id)->update(['state' => "Reserved"]);
          }

          $response["status"] = 1;
          $response["message"] = "Loan and all its dependencies were correctly created.";
          return json_encode($response);

        }
        else
        {
          // There are no enough devices to create the loan
          $response["status"] = 2;
          $response["message"] = "There are no enough devices to create the loan";
          return json_encode($response);
        }
      }
      else
      {
        // There is no one available
        $response["status"] = 2;
        $response["message"] = "There is no devices available of that model";
        return json_encode($response);
      }


      // $dates = explode("-", $dates);
      // $response["status"] = 1;
      // $response["message"] = Carbon::parse($dates[1]);
      // return json_encode($response);

      $response["status"] = 3;
      $response["message"] = "Nothing is happening";
      return json_encode($response);
    }

    private function transformToBoolean($value)
    {
      return  $value == "true" ? true : false;
    }
}
