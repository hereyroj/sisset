<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class NotificarPreAsignacionRechazada extends Mailable
{
    use Queueable, SerializesModels;

    public $solicitud;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($solicitud)
    {
        $this->solicitud = $solicitud;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('admin.correos.notificarPreAsignacionRechazada')->subject('Solicitud de pre-asignación rechazada');
    }
}
