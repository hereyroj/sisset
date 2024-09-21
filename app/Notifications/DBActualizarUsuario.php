<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use App\User;

class DBActualizarUsuario extends Notification implements ShouldQueue
{
    use Queueable;

    public $usuario;

    public $administrador;

    public function __construct(User $usuario, User $administrador)
    {
        $this->usuario = $usuario;
        $this->administrador = $administrador;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database', 'broadcast'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)->line('The introduction to the notification.')->action('Notification Action', 'https://laravel.com')->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'usuarioActualizado_id' => $this->usuario->id,
            'usuarioActualizado_name' => $this->usuario->name,
            'administrador_id' => $this->administrador->id,
            'administrador_name' => $this->administrador->name,
        ];
    }
}
