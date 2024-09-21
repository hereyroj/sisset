<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\placa;
use App\solicitud_preasignacion;

class NotificarPreAsignacion extends Mailable
{
    use Queueable, SerializesModels;

    public $ev;

    public $sp;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($ev, $sp)
    {
        $this->ev = $ev;
        $this->sp = $sp;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('admin.correos.notificarPreAsignacion')->subject('Se ha realizado la pre-asignación');
    }
}
