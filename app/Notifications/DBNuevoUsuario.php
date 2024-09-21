<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\Auth;
use App\User;

class DBNuevoUsuario extends Notification implements ShouldQueue
{
    use Queueable;

    private $nuevoUsuario;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(User $nuevoUsuario)
    {
        $this->nuevoUsuario = $nuevoUsuario;
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
            'nuevoUsuario_id' => $this->nuevoUsuario->id,
            'nuevoUsuario_name' => $this->nuevoUsuario->name,
            'administrador_id' => Auth::user()->id,
            'administrador_name' => Auth::user()->name,
        ];
    }
}
