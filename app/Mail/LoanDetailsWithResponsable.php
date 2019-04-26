<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class LoanDetailsWithResponsable extends Mailable
{
    use Queueable, SerializesModels;

    public $loanID;
    public $model;
    public $quantity;
    public $applicant;
    public $email;
    public $responsableName;
    public $responsableEmail;
    public $reason;
    public $applicantID;
    public $startDate;
    public $endDate;
    public $sendModelName;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($loanID, $model, $quantity, $applicant, $email, $responsableName, $responsableEmail, $reason, $applicantID, $startDate, $endDate, $sendModelName)
    {
        $this->loanID = $loanID;
        $this->model = $model;
        $this->quantity = $quantity;
        $this->applicant = $applicant;
        $this->email = $email;
        $this->responsableName = $responsableName;
        $this->responsableEmail = $responsableEmail;
        $this->reason = $reason;
        $this->applicantID = $applicantID;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->sendModelName = $sendModelName;
    }

    // public function __construct(Array $mailInfo){
    //     $this->$mailInfo["model"] = $mailInfo[0]["model"];
    // }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from("inventariostec@gmail.com", "Laboratorio de Inventarios Tec")->subject("Haz sido referido en un préstamo")->view('emails.LoanDetailsWithResponsable');
    }
}
