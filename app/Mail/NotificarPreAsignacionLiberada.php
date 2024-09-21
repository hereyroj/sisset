<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class NotificarPreAsignacionLiberada extends Mailable
{
    use Queueable, SerializesModels;

    public $solicitud;

    public $ev;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($solicitud, $ev)
    {
        $this->solicitud = $solicitud;
        $this->ev = $ev;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('admin.correos.notificarPreAsignacionLiberada')->subject('Pre-asignación anulada');
    }
}
