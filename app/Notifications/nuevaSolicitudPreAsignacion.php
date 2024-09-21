<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use App\solicitud_preasignacion;

class nuevaSolicitudPreAsignacion extends Notification implements ShouldQueue
{
    use Queueable;

    public $solicitud;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(solicitud_preasignacion $solicitud)
    {
        $this->solicitud = $solicitud;
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
            'id_solicitud' => $this->solicitud->id,
            'hora_solicitud' => $this->solicitud->created_at->format('Y-m-d H:i:s'),
            'nombre_solicitante' => $this->solicitud->nombre_solicitante,
            'clase_vehiculo' => $this->solicitud->hasVehiculoClase->name,
            'servicio_vehiculo' => $this->solicitud->hasVehiculoServicio->name,
        ];
    }

    public function toBroadcast($notifiable)
    {
        return [
            'id_solicitud' => $this->solicitud->id,
            'hora_solicitud' => $this->solicitud->created_at->format('Y-m-d H:i:s'),
            'nombre_solicitante' => $this->solicitud->nombre_solicitante,
            'clase_vehiculo' => $this->solicitud->hasVehiculoClase->name,
            'servicio_vehiculo' => $this->solicitud->hasVehiculoServicio->name,
        ];
    }
}
