<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class ReasignacionPQR extends Notification implements ShouldQueue
{
    use Queueable;

    public $asignacion;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($asignacion)
    {
        $this->asignacion = $asignacion;
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
            'pqr_id' => $this->asignacion->gd_pqr_id,
            'asignacion_id' => $this->asignacion->id,
            'aviso' => $this->asignacion->fecha_reasignacion,
            'reasignador_id' => $this->asignacion->hasFuncionario->id,
            'reasignador_nombre' => $this->asignacion->hasFuncionario->name,
            'radicado' => $this->asignacion->hasPQR->getRadicadoEntrada->numero,
        ];
    }
}
