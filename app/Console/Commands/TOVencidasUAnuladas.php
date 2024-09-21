<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Mail\notificarTOAnulada;
use App\tarjeta_operacion;
use App\vehiculo;

class TOVencidasUAnuladas extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tos:VencidasUAnuladas';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Comprobar en la base de datos cuales tarjetas de operación ya están vencidas o anuladas.';

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
        /*
         * Anuladas: Aquellas que tienen una TO nueva vigente o duplicado posterior
         * Vencidas: Aquellas cuya fecha de vencimiento son menor a la fecha actual y no tienen una renovación posterior.
         */
        try{
            $vehiculos = vehiculo::with('hasTOS')->get();
            foreach ($vehiculos as $vehiculo){
                $to = $vehiculos->hasTOS->first();
                if($to->fecha_vencimiento < date('Y-m-d')){
                    \Mail::send(new notificarTOAnulada($to));
                }elseif($to->duplicado === 1){
                    \DB::table('tarjeta_operacion')->where('vehiculo_id', $vehiculo->id)->where('id', '!=', $to->id)->update(['anulada'=>'SI']);
                }
            }
        }catch (\Exception $e){
            $this->info($e->getMessage());
        }
    }
}
