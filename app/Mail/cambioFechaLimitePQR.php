<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class cambioFechaLimitePQR extends Mailable
{
    use Queueable, SerializesModels;
    public $pqr = null;
    public $correo = null;
    public $tipoRadicado = null;
    public $radicado = null;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($pqr, $correo)
    {
        $this->pqr = $pqr;
        $this->correo = $correo;
        if($this->pqr->tipo_pqr !== 'CoSa'){
            $this->tipoRadicado = 'Entrada';
            $this->radicado = $this->pqr->getRadicadoEntrada;
        }else{
            $this->tipoRadicado = 'Salida';
            $this->radicado = $this->pqr->getRadicadoSalida;
        }
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('admin.correos.cambioFechaLimitePQR')->subject('Cambio de fecha límite respuesta al proceso PQR');
    }
}
