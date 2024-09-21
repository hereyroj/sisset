<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\mandamiento_notificacion;

class checkMandamientosNotificaciones extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:name';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        try{
            $funcionarios = User::whereHas('hasRoles', function($query){
                $query->where('name', 'Coordinador Coactivo')->orWhere('name', 'Auxiliar Coactivo');
            })->get();
            /*
             * Próximas a vencer
             */
            $mandamientosNotificaciones = mandamiento_notificacion::doesntHave('hasDevolucion')->doesntHave('hasEntrega')->where('fecha_max_presentacion', \Carbon\Carbon::now()->addDays(3)->toDateString())->get();
            foreach ($mandamientosNotificaciones as $notificacion) {
                \Notification::send($funcionarios, new \App\Notifications\mandamientoNotificationAVencer($notificacion));
            }
            /*
             * Vence al final del día
             */
            $mandamientosNotificaciones = mandamiento_notificacion::doesntHave('hasDevolucion')->doesntHave('hasEntrega')->where('fecha_max_presentacion', \Carbon\Carbon::now()>toDateString())->get();
            foreach ($mandamientosNotificaciones as $notificacion) {
                \Notification::send($funcionarios, new \App\Notifications\mandamientoNotificationVencido($notificacion));
            }
            /*
             * Vencidas
             */
            $mandamientosNotificaciones = mandamiento_notificacion::doesntHave('hasDevolucion')->doesntHave('hasEntrega')->where('fecha_max_presentacion', '<', \Carbon\Carbon::now()->toDateString())->get();
            foreach ($mandamientosNotificaciones as $notificacion) {
                \Notification::send($funcionarios, new \App\Notifications\mandamientoNotificationVencimiento($notificacion));
            }
        }catch(\Exception $e){
            $this->info('Ha ocurrido un error en la operacion.');
            $this->info($e->getMessage());
        }        
    }
}
