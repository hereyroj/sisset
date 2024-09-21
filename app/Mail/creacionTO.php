<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\empresa_transporte;
use App\tarjeta_operacion;

class creacionTO extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public $nombre_empresa;

    public $placa;

    public $codigo_to;

    public function __construct($placa, $codigo_to, $nombre_empresa)
    {
        $this->nombre_empresa = $nombre_empresa;
        $this->placa = $placa;
        $this->codigo_to = $codigo_to;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('admin.correos.creacionTO')->subject('Se ha creado una Tarjeta de Operación');
    }
}
