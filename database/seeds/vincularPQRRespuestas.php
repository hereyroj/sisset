<?php

use Illuminate\Database\Seeder;

class vincularPQRRespuestas extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Eloquent::unguard();
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        \DB::table('gd_pqr_respuesta')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $CoSas = \App\gd_pqr::where('tipo_pqr', 'CoSa')->where('radicados_respuesta','!=',null)->where('radicados_respuesta','!=',' ')->orderBy('id','asc')->get();
        foreach ($CoSas as $CoSa){
            $CoSa->radicados_respuesta = str_replace(' ', '', $CoSa->radicados_respuesta);
            $CoSa->save();
            $radicados = explode(',', $CoSa->radicados_respuesta);
            foreach ($radicados as $radicado){
                try{
                    $radicado_respondido = \App\gd_pqr_radicado_entrada::with('hasPQR')->where('numero',$radicado)->first();
                    if($radicado_respondido != null){
                        $radicado_respondido->hasPQR->hasRespuestas()->attach($CoSa->id);
                    }
                }catch (\Exception $e){
                    
                }
            }
        }
    }
}
