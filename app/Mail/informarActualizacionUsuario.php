<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\User;

class informarActualizacionUsuario extends Mailable
{
    use Queueable, SerializesModels;

    public $administrador;

    public $usuario;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(User $administrador, user $usuario)
    {
        $this->administrador = $administrador;
        $this->usuario = $usuario;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('admin.correos.informarActualizacionUsuario')->subject('Se han realizado cambios en tu cuenta');
    }
}
