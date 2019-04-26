<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class LoanSeparatedToUser extends Mailable
{
    use Queueable, SerializesModels;

    public $name;
    public $id;
    public $devicename;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($name, $id, $devicename)
    {
        $this->name = $name;
        $this->id = $id;
        $this->devicename = $devicename;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        // return $this->from("inventariostec@gmail.com", "Laboratorio de Inventarios Tec")->subject("Haz sido referido en un préstamo")->view('emails.LoanDetailsWithResponsable');
        return $this->from("inventariostec@gmail.com", "Laboratorio de Inventarios Tec")->subject("Tu préstamo está listo")->view('emails.LoanSeparatedToUser');
    }
}
