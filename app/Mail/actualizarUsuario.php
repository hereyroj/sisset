<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\User;

class actualizarUsuario extends Mailable
{
    use Queueable, SerializesModels;

    public $usuario;

    public $administrador;

    public function __construct(User $usuarioActualizado, User $administrador)
    {
        $this->usuario = $usuarioActualizado;
        $this->administrador = $administrador;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('admin.correos.actualizarUsuario')->subject('Se ha actualizado un usuario.');
    }
}
