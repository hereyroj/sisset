<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class autorizarEntradaCarpeta extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public $carpeta;

    public $solicitante;

    public $autorizador;

    public $tramite;

    public function __construct($carpeta, $solicitante, $autorizador, $tramite)
    {
        $this->carpeta = $carpeta;
        $this->solicitante = $solicitante;
        $this->autorizador = $autorizador;
        $this->tramite = $tramite;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('admin.correos.autorizarEntradaCarpeta')->subject('Se ha autorizado la entrada de una carpeta del archivo.');
    }
}
