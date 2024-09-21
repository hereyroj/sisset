<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class actualizacionTO extends Mailable
{
    use Queueable, SerializesModels;

    public $placa;

    public $codigo_to;

    public $fecha_modificacion;

    public $nombre_empresa;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($placa, $codigo_to, $fecha_modificacion, $nombre_empresa)
    {
        $this->placa = $placa;
        $this->codigo_to = $codigo_to;
        $this->fecha_modificacion = $fecha_modificacion;
        $this->nombre_empresa = $nombre_empresa;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('admin.correos.actualizacionTO')->subject('Actualización Tarjeta de Operación');
    }
}
