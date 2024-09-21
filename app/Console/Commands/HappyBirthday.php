<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Notifications\Notification;
use App\User;
use Mail;

class HappyBirthday extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send:birthday';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Envia un mensaje de Feliz Cumpleaños a los usuarios';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $users = User::whereMonth('birthdate', '=', date('m'))->whereDay('birthdate', '=', date('d'))->get();

        if ($users->count() > 0) {
            foreach ($users as $user) {
                Mail::to($user)->send(new \App\Mail\HappyBirthday($user->name));
            }
            $this->info('Los mensajes de felicitacion han sido enviados correctamente');
        } else {
            $this->info('Hoy nadie cumple años');
        }
    }
}
