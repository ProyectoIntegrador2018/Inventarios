<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Device;

use DB;

class LoanController extends Controller
{
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
            WHERE d.model = 'iPhone7' AND s.state = 'Available'
            GROUP BY d.model;
        ");

        $modelAvailability = $modelAvailability[0]->quantity;

        if($modelAvailability > 0){
            $response["status"] = 1;
            $response["message"] = "Hay disponibles";
            return json_encode($response);
        }else{
            $response["status"] = 2;
            $response["message"] = "No hay disponibles";
            return json_encode($response);
        }

    }
}
