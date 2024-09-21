<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class notificarTOAnulada extends Mailable
{
    use Queueable, SerializesModels;

    public $to;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($to)
    {
        $this->to = $to;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('admin.correos.anulacionTO')->subject('Tarjeta de Operación Anulada');
    }
}
