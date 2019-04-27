<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class LoanReminder extends Mailable
{
    use Queueable, SerializesModels;

    public $id;
    public $end_date;
    public $name;
    public $email;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($id, $end_date, $name, $email)
    {
        $this->id = $id;
        $this->end_date = $end_date;
        $this->name = $name;
        $this->email = $email;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from("inventariostec@gmail.com", "Laboratorio de Inventarios Tec")->subject("Tu préstamo se encuentra próximo a finalizar")->view('emails.LoanReminder');
    }

}
