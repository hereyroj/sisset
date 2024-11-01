<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class NuevoWebPQR extends Notification implements ShouldQueue
{
    use Queueable;

    public $pqr;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($pqr)
    {
        $this->pqr = $pqr;
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
            'pqr_id' => $this->pqr->id,
            'radicacion' => $this->pqr->created_at->format('Y-m-d H:i:s'),
            'clase' => $this->pqr->hasClase->name,
        ];
    }
}
