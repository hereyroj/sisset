<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class turnoAsignado implements ShouldBroadcast
{
    use InteractsWithSockets, SerializesModels;

    public $turno;

    public $ventanilla;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($turno, $ventanilla)
    {
        $this->turno = $turno;
        $this->ventanilla = $ventanilla;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel|array
     */
    public function broadcastOn()
    {
        return new Channel('turnos');
    }
}
