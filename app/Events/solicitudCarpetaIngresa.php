<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class solicitudCarpetaIngresa implements ShouldBroadcast
{
    use InteractsWithSockets, SerializesModels;

    public $archivo_solicitud;

    public $archivo_carpeta_prestamo;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($solicitud, $carpeta)
    {
        $this->archivo_carpeta_prestamo = $carpeta;
        $this->archivo_solicitud = $solicitud;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('App.Solicitudes');
    }
}
