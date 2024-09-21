<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class FuncionarioPQR implements ShouldBroadcast
{
    use InteractsWithSockets, SerializesModels;
    public $funcionarioId;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($id)
    {
        $this->funcionarioId = $id;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('App.misPQR.'.$this->funcionarioId);
    }
}
