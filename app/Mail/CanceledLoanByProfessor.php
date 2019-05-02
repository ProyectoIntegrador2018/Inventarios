<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class CanceledLoanByProfessor extends Mailable
{
    use Queueable, SerializesModels;

    public $loanID;
    public $applicant;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($loanID, $applicant)
    {
        $this->loanID = $loanID;
        $this->applicant = $applicant;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from("inventariostec@gmail.com", "Laboratorio de Inventarios Tec")->subject("Tu préstamo ha sido cancelado")->view('emails.CanceledLoanByProfessor');
    }
}
