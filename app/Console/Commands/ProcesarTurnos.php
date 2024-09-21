<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;
use App\tramite_solicitud_turno;

class ProcesarTurnos extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'turnos:procesar';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Procesa los turnos del día. Determina si deben ser marcados como vencidos o anulados.';

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
        $turnos_vencidos = 0;
        $turnos_anulados = 0;
        try {
            $turnos_a_procesar = tramite_solicitud_turno::where('fecha_atencion', null)->where('fecha_anulacion', null)->where('fecha_vencimiento', null)->get();
            if ($turnos_a_procesar->count() > 0) {
                foreach ($turnos_a_procesar as $turno) {
                    if ($turno->fecha_llamado != null) {
                        if (Carbon::createFromFormat('Y-m-d H:i:s', $turno->fecha_llamado)->addHour(1) < Carbon::now() || Carbon::createFromFormat('Y-m-d H:i:s', $turno->created_at)->addHour(1) < Carbon::now()) {
                            $turno->fecha_anulacion = date('Y-m-d H:i:s');
                            $turno->save();
                            $turnos_anulados++;
                        }
                    } else {
                        $turno->fecha_vencimiento = date('Y-m-d H:i:s');
                        $turno->save();
                        $turnos_vencidos++;
                    }
                }
                $this->info('Se ha completado la operación. Se han procesado '.count($turnos_a_procesar).', dando como resultado: vencidos '.$turnos_vencidos.', anulados: '.$turnos_anulados);
            } else {
                $this->info('No hay turnos para procesar.');
            }
        } catch (\Exception $e) {
            $this->info('Ha ocurrido un error en la operacion.');
            $this->info($e->getMessage());
        }

        /*
         * Cierre de ventanillas
         */
        \DB::table('ventanilla_funcionario')->where('fecha_retiro', null)->orWhere('libre', 'NO')->update(['libre'=>'SI', 'fecha_retiro' => date('Y-m-d H:i:s')]);
    }
}
