<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\tramite_solicitud;
use App\tramite_servicio;

class TramiteServicioRecibos extends Mailable
{
    use Queueable, SerializesModels;
    public $tramite_solicitud;
    public $tramite_servicio;
    public $usuario;
    public $correo;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($solicitud, $servicio, $correo)
    {
        $this->tramite_solicitud = $solicitud;
        $this->tramite_servicio = $servicio;
        $this->usuario = $this->tramite_solicitud->hasTurnoActivo()->hasUsuarioSolicitante;
        $this->correo = $correo;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('admin.correos.tramiteServicioRecibos')->subject('Se han cargado recibos a su solicitud de tramite.')->to($this->correo);
    }
}
