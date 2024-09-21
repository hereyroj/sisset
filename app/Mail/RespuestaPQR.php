<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class RespuestaPQR extends Mailable
{
    use Queueable, SerializesModels;

    public $pqr;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($pqr)
    {
        $this->pqr = $pqr;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('admin.correos.respuestaPQR')->subject('Respuesta a proceso PQR con radicado '.$this->pqr->getRadicadoEntrada->numero)->to($this->pqr->hasPeticionario->correo_notificacion);
    }
}
