<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class radicadoPQR extends Mailable
{
    use Queueable, SerializesModels;

    public $pqr;
    private $email;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($pqr, $email)
    {
        $this->pqr = $pqr;
        $this->email = $email;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('admin.correos.radicadoPQR')->subject('Radicado PQR')->to($this->email);
    }
}
