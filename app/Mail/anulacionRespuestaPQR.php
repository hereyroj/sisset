<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class anulacionRespuestaPQR extends Mailable
{
    use Queueable, SerializesModels;
    public $pqrRespondido, $pqrRespuesta, $correoDestinatario;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($pqrRespuesta, $pqrRespondido, $correoDestinatario)
    {
        $this->pqrRespondido = $pqrRespondido;
        $this->pqrRespuesta = $pqrRespuesta;
        $this->correoDestinatario = $correoDestinatario;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('admin.correos.anulacionRespuestaPQR')->subject('Se ha anulado la respuesta a su proceso PQR')->to($this->correoDestinatario);
    }
}
