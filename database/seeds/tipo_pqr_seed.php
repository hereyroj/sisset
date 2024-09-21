<?php

use Illuminate\Database\Seeder;
use App\gd_pqr_clase;

class tipo_pqr_seed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $tipos = [
            'RECLAMO',
            'QUEJA',
            'DENUNCIA',
            'SUGERENCIA',
            'PETICIÓN',
            'CONSULTA'
        ];

        foreach ($tipos as $tipo){
            gd_pqr_clase::updateOrcreate([
                'name' => $tipo,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ]);
        }
    }
}
