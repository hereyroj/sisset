<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class DesvinculacionRadicadoRespuesta extends Mailable
{
    use Queueable, SerializesModels;
    public $pqr;
    public $radicadoAnterior;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($pqr, $radicadoAnterior)
    {
        $this->pqr = $pqr;
        $this->radicadoAnterior = $radicadoAnterior;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('admin.correos.DesvinculacionRadicadoRespuesta')->subject('Cambio al proceso PQR con radicado de entrada '.$this->pqr->getRadicadoEntrada->numero)->to($this->pqr->hasPeticionario->correo_notificacion);
    }
}
