<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Notifications\Notification;
use App\gd_pqr;
use App\User;

class PrevioAvisoPQR extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pqr:prealertar';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Pre-Alertar PQR que está por vencer y no se le ha dado respuesta.';

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
        $pqrs = gd_pqr::with('getRadicadoEntrada')->doesntHave('hasRespuestas')->doesntHave('hasAnulacion')->where('tipo_pqr', '!=', 'CoSa')->whereHas('hasClase', function ($query){
            $query->where('required_answer','SI');
        })->where('previo_aviso', null)->get();

        if ($pqrs->count() > 0) {
            $administradoresPQR = User::whereHas('hasRoles', function ($query) {
                $query->where('name', '=', 'Administrador PQR');
            })->get();

            foreach ($pqrs as $pqr) {
                if ($pqr->diasRestantes() <= 2 && $pqr->diasPasados() == 0) {
                    $pqr->previo_aviso = date('Y-m-d H:i:s');
                    $pqr->save();
                    $asignacionesActivas = $pqr->getAsignacionesActivas();
                    if ($asignacionesActivas != null) {
                        foreach ($asignacionesActivas as $asignacionActiva){
                            \Notification::send($asignacionActiva->hasUsuarioAsignado, new \App\Notifications\FuncionarioPrevioAvisoPQR($pqr));
                        }
                    }

                    if ($administradoresPQR->count() > 0) {
                        \Notification::send($administradoresPQR, new \App\Notifications\PrevioAvisoPQR($pqr));
                    }
                }elseif($pqr->diasPasados() > 0){
                    $pqr->previo_aviso = date('Y-m-d H:i:s');
                    $pqr->save();
                    $asignacionesActivas = $pqr->getAsignacionesActivas();
                    if ($asignacionesActivas != null) {
                        foreach ($asignacionesActivas as $asignacionActiva){
                            \Notification::send($asignacionActiva->hasUsuarioAsignado, new \App\Notifications\FuncionarioAvisoPQRVencida($pqr));
                        }
                    }

                    if ($administradoresPQR->count() > 0) {
                        \Notification::send($administradoresPQR, new \App\Notifications\AvisoPQRVencida($pqr));
                    }
                }
            }
            $this->info('Se ha terminado la operación con un total de '.count($pqrs).' Pre-Alertados.');
        } else {
            $this->info('No hay procesos de PQR por Pre-Alertar.');
        }
    }
}
