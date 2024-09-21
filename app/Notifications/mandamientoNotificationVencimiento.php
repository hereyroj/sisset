<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class mandamientoNotificationVencimiento extends Notification
{
    use Queueable;
    public $notificacion;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($notificacion)
    {
        $this->notificacion = $notificacion;
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
                    ->line('La notificación número '.$this->notificacion->consecutivo.' del mandamiento de pago número '.$this->notificacion->hasMandamientoPago->consecutivo.' está a punto de vencerse sin que se haya aún registrado información de entrega o devolución.')
                    ->action('Ver notificación', url('/admin/coactivo/mandamientos/filtrar?parametro=numero&filtro='.$this->notificacion->hasMandamientoPago->consecutivo));
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
            'fecha_aviso' => date('Y-m-d H:i:s'),
            'notificacion_id' => $this->notificacion->id,
            'notificacion_numero' => $this->notificacion->consecutivo,
            'mandamiento_pago_id' => $this->notificacion->hasMandamientoPago->id,
            'mandamiento_pago_numero' => $this->notificacion->hasMandamientoPago->consecutivo
        ];
    }
}
