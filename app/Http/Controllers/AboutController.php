<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Mail\LoanReminder;

use App\Loan;
use App\State;

use Mail;

use DB;

class AboutController extends Controller
{
  public function viewAbout()
  {
      return view('about');
  }

  public function getInventory()
  {
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

    // $loanID = $request->input('loanID');

    $loanToCheck = DB::select("
      SELECT status
      FROM loans
      WHERE id = '$loanID'
    ");

    $statusToVerify = $loanToCheck[0]->status;

    //Si el estado actual de el préstamo es cancelado no permirtir hacer la siguiente linea

    if($statusToVerify == "Pending"){
      
      Loan::where('id', $loanID)->update(['status' => "New"]);
      
    }else{

      // Hacer else para cuando se tenga que devolver el mensaje [Hacer variable de mensaje y no dejar el mensaje de abajo estático]
      
      $defaultMessage = "El préstamo seleccionado no es nuevo, la operación no es posible de realizar.";

    }

    return view('loan-accepted')->with('loanID', $loanID)->with('message', $defaultMessage);
  }

  public function declineLoan($loanID){
    
    Loan::where('id', $loanID)->update(['status' => "Cancelled"]);

    $device_ids = DB::select("
      SELECT d.id
      FROM loans l JOIN loan_device ld ON l.id = ld.loan_id
      JOIN devices d ON d.id = ld.device_id
      JOIN states s ON s.device_id = d.id
      WHERE l.id = '$loanID';
    ");

    foreach ($device_ids as $device_id) {
      State::where('device_id', $device_id->id)->update(['state' => "Available"]);
    }

    $defaultMessage = "El préstamo ha sido cancelado y los dispositivos han regresado a estar disponibles.";

    return view('loan-declined')->with('loanID', $loanID)->with('message', $defaultMessage);

  }

  public function sendLoanReminders(){
    
    /*
    Mail::send([], [], function($message){

      $message->from('prueba@laravel.com', 'Prueba de Laravel');

      $message->to('luis_alfonso_96@hotmail.com');

      $message->replyTo('luisandroid09@gmail.com', 'Luis Rojo');

      $message->subject('Prueba para ver si llega el mensaje');

      $message->setBody('Llegó', 'text/html');

    });
    */

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
