<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class TramiteServicioEstado extends Mailable
{
    use Queueable, SerializesModels;
    public $estado;
    public $correo;
    public $turno;
    public $nombre;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($estado, $nombre, $correo, $turno)
    {
        $this->estado = $estado;
        $this->correo = $correo;
        $this->turno = $turno;
        $this->nombre = $nombre;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('admin.correos.tramiteServicioEstado')->subject('Se ha asignado un nuevo estado a su solicitud de tramite.')->to($this->correo);
    }
}
