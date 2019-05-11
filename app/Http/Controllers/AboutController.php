<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Mail\LoanReminder;
use App\Loan;
use App\State;
use App\Mail\CanceledLoanByProfessor;
use Mail;
use DB;

class AboutController extends Controller
{
  public function viewAbout() {
      return view('about');
  }

  public function getInventory() {
    $devices = DB::select("
      SELECT COUNT(d.id) as quantity, d.name, d.brand, d.model
      FROM devices d JOIN states s
      ON d.id = s.device_id
      WHERE s.state = 'Available'
      GROUP BY d.name, d.brand, d.model
    ");

    $quantity = count($devices);

    return view('inventory-guest')->with('devices', $devices)->with('quantity', $quantity);
  }

  public function acceptLoan($loanID){

    $defaultMessage = "La responsabilidad del préstamo ha sido aceptada.";
    $loanToCheck = $this->getLoanStatus($loanID);

    if(count($loanToCheck) != 0){
      $loanStatus = $loanToCheck[0]->status;

      //Si el estado actual de el préstamo es cancelado no permirtir hacer la siguiente linea
      if($loanStatus == "Pending") {
        $this->setLoanStatus($loanID, "New");
      } else {
        // Hacer else para cuando se tenga que devolver el mensaje [Hacer variable de mensaje y no dejar el mensaje de abajo estático]
        $defaultMessage = "El préstamo seleccionado no es nuevo, la operación no es posible de realizar.";
      }
    } else {
      $defaultMessage = "El préstamo seleccionado no ha podido ser cancelado, posiblemente ya fue cancelado. Verificar su estado en el buscador de préstamos.";
    }
    return view('loan-accepted')->with('loanID', $loanID)->with('message', $defaultMessage);
  }

  public function declineLoan($loanID){

    $defaultMessage = "El préstamo ha sido cancelado y los dispositivos han regresado a estar disponibles.";
    $loanToCheck = $this->getLoanStatus($loanID);

    if(count($loanToCheck) != 0){

      $loanStatus = $loanToCheck[0]->status;

      if($loanStatus != "Cancelled"){
        // Cancel loan
        $this->setLoanStatus($loanID, "Cancelled");

        // Release Devices
        $devices = $this->getDevicesFromLoan($loanID);
        $this->setDeviceStatus($devices, "Available");

        // Send email
        $applicant = $this->getAplicantFromLoan($loanID);
        \Mail::to($applicant[0]->email)->send(new CanceledLoanByProfessor($loanID, $applicant[0]->name));

      } else {
        $defaultMessage = "El préstamo seleccionado no ha podido ser cancelado, posiblemente ya fue cancelado. Verificar su estado en el buscador de préstamos.";
      }
    } else {
      $defaultMessage = "Operación no permitida, el préstamo no se encuentra registrado.";
    }
    return view('loan-declined')->with('loanID', $loanID)->with('message', $defaultMessage);
  }

  private function setLoanStatus($loanID, $newStatus) {
    Loan::where('id', $loanID)->update(['status' => $newStatus]);
  }

  public function setDeviceStatus($devices, $newStatus) {
    foreach ($devices as $deviceID) {
      State::where('device_id', $deviceID->id)->update(['state' => $newStatus]);
    }
  }

  public function getDevicesFromLoan($loanID) {
    $devices = DB::select("
      SELECT d.id
      FROM loans l JOIN loan_device ld ON l.id = ld.loan_id
      JOIN devices d ON d.id = ld.device_id
      JOIN states s ON s.device_id = d.id
      WHERE l.id = '$loanID';
    ");

    return $devices;
  }

  public function getAplicantFromLoan($loanID) {
    $applicantInfo = DB::select("
      SELECT a.name, a.email
      FROM loans l
      JOIN applicants a ON l.applicant_id = a.id
      WHERE l.id = '$loanID';
    ");

    return $applicantInfo;
  }

  public function getLoanStatus($loanID) {
    $loanStatus = DB::select("
      SELECT status
      FROM loans
      WHERE id = '$loanID'
    ");

    return $loanStatus;
  }

  public function sendLoanReminders(){
    $remaindersToSend = DB::select("
      SELECT l.id, l.end_date, ap.name, ap.email
      FROM loans l
      JOIN applicants ap ON l.applicant_id = ap.id
      WHERE end_date >= current_date
      ;
    ");

    foreach ($remaindersToSend as $remainderToSend) {
      \Mail::to($remainderToSend->email)->send(new LoanReminder($remainderToSend->id, $remainderToSend->end_date, $remainderToSend->name, $remainderToSend->email));
    }
  }

}
