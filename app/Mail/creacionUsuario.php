<?php

namespace App\Mail;

use Artesaos\Defender\Role;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\User;

class creacionUsuario extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public $usuario;

    public $administrador;

    public function __construct(User $usuario, User $notifiable)
    {
        $this->usuario = $usuario;
        $this->administrador = $notifiable;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('admin.correos.creacionUsuario')->subject('Se ha creado un nuevo usuario.');
    }
}
