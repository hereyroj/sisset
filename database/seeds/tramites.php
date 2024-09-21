<?php

use Illuminate\Database\Seeder;

class tramites extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $tramites = [
            'DUPLICADO DE LA LICENCIA DE TRANSITO ',
            'TRASPASO',
            'MATRICULA INICIAL',
            'TRASPASO A PERSONA INDETERMINADA',
            'TRASLADO DE CUENTA',
            'LEVANTAMIENTO O INSCRIPCION DE PRENDA',
            'DUPLICADO DE PLACA',
            'CANCELACION DE LA MATRICULA',
            'RADICADO DE CUENTA',
            'CAMBIO DE COLOR',
            'CAMBIO DE MOTOR',
            'CERTIFICADO DE TRADICION',
            'REMATRICULA'
        ];

        $limit = count($tramites);
        for($i=0;$i<$limit;$i++){
            DB::table('tramite')->insert([
                'name' => $tramites[$i],
                'created_at' => date('Y-m-d H:i:s'),
            ]);
        }
    }
}
