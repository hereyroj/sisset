<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class mandamientoSinFinalizacion extends Notification
{
    use Queueable;
    public $mandamiento;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($mandamiento)
    {
        $this->mandamiento = $mandamiento;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail', 'database', 'broadcast'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->line('El mandamiento de pago número '.$this->mandamiento->consecutivo.' hasta la fecha no cuenta con un registro de finalización.')
                    ->action('Ver Mandamiento Pago', url('/admin/coactivo/mandamientos/filtrar/?param=numero&filtro='.$this->mandamiento->consecutivo));
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'fecha_notificacion' => date('Y-m-d H:i:s'),
            'mandamiento_pago_id' => $this->mandamiento->id,
            'mandamiento_pago_numero' => $this->mandamiento->consecutivo
        ];
    }
}
