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

use App\Mail\LoanDetailsWithResponsable;
use App\Mail\LoanDetailsWithoutResponsable;
use App\Mail\LoanSeparatedToUser;
use App\Mail\CanceledLoan;

use Mail;

class LoanController extends Controller{

  public function viewSearchLoan(){
    return view('show-loan');
  }

  public function viewLoanRequest ($requestedDevice){
    $serialNumbers = DB::select("SELECT d.serial_number FROM devices d JOIN states s ON d.id = s.device_id WHERE d.model = '$requestedDevice' AND s.state = 'Available';");
    $modelInformation = DB::select("SELECT d.name, d.brand, d.model FROM devices d JOIN states s ON d.id = s.device_id WHERE d.model = '$requestedDevice' AND s.state = 'Available' GROUP BY d.name, d.brand, d.model;");
    if(count($modelInformation) > 0){
      $modelInformation = $modelInformation[0];
      $quantity = count($serialNumbers);
      return view('request-loan')->with('serialNumbers', $serialNumbers)->with('modelInformation', $modelInformation)->with('quantity', $quantity);
    }else{
      $modelInformationUnavailable = DB::select("SELECT d.name, d.brand, d.model FROM devices d JOIN states s ON d.id = s.device_id WHERE d.model = '$requestedDevice'");
      $quantity = count($serialNumbers);
      $modelInformationUnavailable = $modelInformationUnavailable[0]; 
      return view('request-loan')->with('serialNumbers', [])->with('modelInformation', $modelInformationUnavailable)->with('quantity', $quantity);
    }
  }

  public function setLoanStatus(Request $request){
    $loanID = $request->input('loanID');
    $loanStatus = $request->input('loanStatus');
    switch ($loanStatus){
      case "New":
        Loan::where('id', $loanID)->update(['status' => "Separated"]);
        $informationToSend = DB::select("SELECT a.email, a.name, l.id, d.name AS devicename FROM applicants a JOIN loans l ON a.id = l.applicant_id JOIN loan_device ld ON ld.loan_id = l.id JOIN devices d ON d.id = ld.device_id WHERE l.id = '$loanID';");
        \Mail::to($informationToSend[0]->email)->send(new LoanSeparatedToUser($informationToSend[0]->name,$informationToSend[0]->id,$informationToSend[0]->devicename));
      break;
      case "Separated":
        Loan::where('id', $loanID)->update(['status' => "Taken"]);
      break;
      case "Taken":
        Loan::where('id', $loanID)->update(['status' => "Received"]);
        $device_ids = DB::select("SELECT d.id FROM loans l JOIN loan_device ld ON l.id = ld.loan_id JOIN devices d ON d.id = ld.device_id JOIN states s ON s.device_id = d.id WHERE l.id = '$loanID';");
        foreach ($device_ids as $device_id){
          $newStatus = "Available";
          State::where('device_id', $device_id->id)->update(['state' => $newStatus]);
        }
      break;
      case "Expired":
        Loan::where('id', $loanID)->update(['status' => "Received late"]);
      break;
      case "Received":
      case "Received late":
      case "Cancelled":
      case "Unknown status":
      break;
    }

    $response["status"] = 1;
    $response["message"] = "Loan status successfully updated";
    return json_encode($response);
  }

  public function getLoansToCSV(Request $request){ 
    $request["allDates"] = $this->transformToBoolean($request["allDates"]);
    $request["professor"] = $this->transformToBoolean($request["professor"]);
    $request["student"] = $this->transformToBoolean($request["student"]);
    $request["allStatus"] = $this->transformToBoolean($request["allStatus"]);
    $dates = array('selectAll' => $request["allDates"],'start' => $request["startDate"], 'end' => $request["endDate"]);
    $solicitants = array('professor' => $request["professor"],'student' => $request["student"]);
    $status = array('selectAll' => $request["allStatus"], 'statuses' => $request["statuses"]);
    $inputs = array('dates' => $dates, 'solicitants' => $solicitants, 'status' => $status);
    $fileName = 'Reporte-prestamos';
    if($request["allDates"]){
      $fileName = "{$fileName}_Historico";
    }else{
      $startDate = str_replace('/', "-", $request["startDate"]);
      $endDate = str_replace('/', "-", $request["endDate"]);
      $fileName = "{$fileName}_del_{$startDate}_al_{$endDate}";
    }
    return Excel::download(new LoansExport($inputs), "{$fileName}.xlsx");
  }

  public function getDeviceNameFromLoan($loanID){
    $deviceNames = DB::select("SELECT d.name FROM devices d GROUP BY d.name;");
    return array($deviceNames);
  }

  public function getLoanFromID(Request $request){
    $loanID = $request->input('txb_search');
    $loan = DB::select("SELECT l.start_date, l.end_date, l.status FROM loans l WHERE l.id = '$loanID';");
    if(sizeof($loan) > 0){
      $applicant = DB::select("SELECT a.name, a.applicant_id, a.degree FROM loans l JOIN applicants a ON l.applicant_id = a.id WHERE l.id = '$loanID';");
      $responsable = DB::select("SELECT r.name FROM loans l JOIN applicants a ON l.applicant_id = a.id LEFT OUTER JOIN responsables r ON a.id = r.applicant_id WHERE l.id = '$loanID';");
      $device = DB::select("SELECT d.name, d.brand, d.model FROM loans l JOIN loan_device ld ON l.id = ld.loan_id JOIN devices d ON ld.device_id = d.id WHERE l.id = '$loanID';");
      $response = array('status' => 'SUCCESS', 'device' => $device[0], 'loan' => $loan[0], 'applicant' => $applicant[0], 'responsable' => $responsable[0]);
    }else{
      $response = array( 'status' => 'NOT FOUND' );
    }
    return json_encode($response);
  }

  public function cancelLoan(Request $request){
    $loanID     = $request->input('loanID');
    $loanStatus = $request->input('loanStatus');
    Loan::where('id', $loanID)->update(['status' => "Cancelled"]);
    $device_ids = DB::select("SELECT d.id FROM loans l JOIN loan_device ld ON l.id = ld.loan_id JOIN devices d ON d.id = ld.device_id JOIN states s ON s.device_id = d.id WHERE l.id = '$loanID';");
    foreach ($device_ids as $device_id) {
      $newStatus = "Available";
      State::where('device_id', $device_id->id)->update(['state' => $newStatus]);
    }
    $emailToSendMessage = DB::select("SELECT a.name, a.email FROM loans l JOIN applicants a ON l.applicant_id = a.id WHERE l.id = '$loanID';");
    \Mail::to($emailToSendMessage[0]->email)->send(new CanceledLoan($loanID, $emailToSendMessage[0]->name));
    $response["status"] = 1;
    $response["message"] = "Loan successfully cancelled";
    return json_encode($response);
  }

  public function createLoan(Request $request){

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

    $modelAvailability = DB::select("SELECT COUNT(d.model) as quantity FROM devices d JOIN states s ON d.id = s.device_id WHERE d.model = '$model' AND s.state = 'Available' GROUP BY d.model;");

    $modelAvailability = $modelAvailability[0]->quantity;

    if($modelAvailability > 0){
      if($quantity <= $modelAvailability){

        DB::table('applicants')->insert(['name' => $applicant, 'email' => $email, 'applicant_id' => $applicantID, 'degree' => $bachelor, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()]);
        $lastApplicantID = DB::table('applicants')->orderBy('id', 'desc')->first();
        $lastApplicantID = $lastApplicantID->id;
        $sendEmailWithResponsableProfessor = true;

        if($isStudent == 1){
          DB::table('responsables')->insert(['name' => $responsableName, 'email' => $responsableEmail, 'applicant_id' => $lastApplicantID, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()]);
        }else{
          $sendEmailWithResponsableProfessor = false;
        }

        $dates = explode("-", $dates);
        
        if($isStudent == 1){
          DB::table('loans')->insert(['start_date' => Carbon::parse($dates[0]), 'end_date' => Carbon::parse($dates[1]), 'loan_date' => Carbon::now(), 'return_date' => Carbon::parse($dates[1]), 'status' => 'Pending', 'reason' => $reason, 'applicant_id' => $lastApplicantID, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()]);
          $modelName = DB::select("SELECT name FROM devices WHERE model = '$model' LIMIT 1;");
          $sendModelName = $modelName[0]->name;
          $lastLoanID = DB::table('loans')->orderBy('id', 'desc')->first();
          $loanToSend = $lastLoanID->id;
          \Mail::to($responsableEmail)->send(new LoanDetailsWithResponsable($loanToSend, $model, $quantity, $applicant, $email, $responsableName, $responsableEmail, $reason, $applicantID, Carbon::parse($dates[0])->format('Y-m-d H:i'), Carbon::parse($dates[1])->format('Y-m-d H:i'), $sendModelName));
        }else{
          DB::table('loans')->insert(['start_date' => Carbon::parse($dates[0]), 'end_date' => Carbon::parse($dates[1]), 'loan_date' => Carbon::now(), 'return_date' => Carbon::parse($dates[1]), 'status' => 'New', 'reason' => $reason, 'applicant_id' => $lastApplicantID, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()]);
        }

        $lastLoanID = DB::table('loans')->orderBy('id', 'desc')->first();
        $devicesToReserve = DB::select("SELECT d.* FROM devices d JOIN states s ON d.id = s.device_id WHERE d.model = '$model' AND s.state = 'Available' LIMIT '$quantity';");

        for($x = 0; $x < $quantity; $x++){
          DB::table('loan_device')->insert(['loan_id' => $lastLoanID->id, 'device_id' => $devicesToReserve[$x]->id, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()]);
          $lastLoanDeviceID = DB::table('loan_device')->orderBy('id', 'desc')->first();
          State::where('device_id', $lastLoanDeviceID->device_id)->update(['state' => "Reserved"]);
        }

        $response["status"] = 1;
        $response["message"] = "Loan and all its dependencies were correctly created.";
        return json_encode($response);
      }else{
        $response["status"] = 2;
        $response["message"] = "There are no enough devices to create the loan";
        return json_encode($response);
      }
    }else{
      $response["status"] = 2;
      $response["message"] = "There is no devices available of that model";
      return json_encode($response);
    }

    $response["status"] = 3;
    $response["message"] = "Nothing is happening";
    return json_encode($response);
  }

  private function transformToBoolean($value){
    return  $value == "true" ? true : false;
  }
}
