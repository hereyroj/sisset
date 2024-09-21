<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;
use App\solicitud_preasignacion;

class ProcesarPreAsignaciones extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'preasignaciones:procesar';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Liberar las preasinaciones que tenga mas de 60 dias sin matricular.';

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
        $preAsignaciones = solicitud_preasignacion::whereHas('hasPlacas', function ($query){
            $query->where('fecha_matricula', null)->where('fecha_liberacion', null)->wherePivot('created_at', '<', Carbon::now()->subDays(60)->toDateString());
        })->get();
        foreach ($preAsignaciones as $preAsignacion){
            $preAsignacion->pivot->fecha_liberacion = date('Y-m-d H:i:s');
            $preAsignacion->pivot->save();
        }
        $this->info('Se han liberado '.$preAsignaciones->count(). ' preasignaciones.');
    }
}
