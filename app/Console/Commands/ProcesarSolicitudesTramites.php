<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;
use App\tramite_solicitud;

class ProcesarSolicitudesTramites extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'SolicitudesTramites:procesar';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Anular todas las solicitudes de tramites que lleven varios dias sin aun resolversen.';

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
        $solicitudes = tramite_solicitud::whereHas('hasUltimaAtencion', function ($query){
            $query->whereBetween('terminacion', [2,3,4])->whereDate('created_at', '<', Carbon::now()->subDays(30)->toDateString());
        })->get();
        foreach ($solicitudes as $solicitud){
            $atencion = $solicitud->hasUltimaAtencion;
            $atencion->observacion = $atencion->observacion.' ANULADO POR SISTEMA DEBIDO A TERMINOS DE ESPERA.';
            $atencion->terminacion = 5;
            $atencion->save();
        }
        $this->info('Se han anulado '.$solicitudes->count(). ' solicitudes de trámites.');
    }
}
