<?php

use Illuminate\Database\Seeder;
use App\gd_pqr_anulacion;
use App\gd_pqr_anulacion_motivo;

class PqrAnulacion extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        try{
            $pqrsAnulados = \DB::table('gd_pqr')->where('deleted_at', '!=', null)->get();
            $anulacionMotivo = gd_pqr_anulacion_motivo::firstOrCreate([
                'name' => 'ERROR DE DOCUMENTO'
            ]);
            \DB::beginTransaction();
            foreach ($pqrsAnulados as $pqr) {
                gd_pqr_anulacion::create([
                    'gd_pqr_id' => $pqr->id,
                    'gd_pqr_anulacion_mo_id' => $anulacionMotivo->id,
                    'observation' => 'Carga errónea de documento radicado.',
                    'funcionario_id' => 1,
                    'created_at' => $pqr->deleted_at,
                    'updated_at' => $pqr->deleted_at
                ]);                
            }
            \DB::table('gd_pqr')->where('deleted_at', '!=', null)->update(['deleted_at' => null]);
            \DB::commit();
        }catch(\Exception $e){
            \DB::rollback();
        } 
    }
}
