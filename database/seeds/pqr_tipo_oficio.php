<?php

use Illuminate\Database\Seeder;

class pqr_tipo_oficio extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $tipos = [
            ['NAME'=>'TUTELA', 'DIAS'=>'1', 'CLASE'=>''],
            ['NAME'=>'DERECHO DE PETICION', 'CLASE'=>''],
            ['NAME'=>'RADICADO DE CUENTA', 'DIAS'=>'', 'CLASE'=>''],
            ['NAME'=>'DERECHO DE PETICION', 'DIAS'=>'', 'CLASE'=>''],
            ['NAME'=>'ACCION DE TUTELA', 'DIAS'=>'', 'CLASE'=>''],
            ['NAME'=>'TRASLADO DE CUENTA', 'DIAS'=>'', 'CLASE'=>''],
            ['NAME'=>'TARJETA DE OPERACION', 'DIAS'=>'', 'CLASE'=>''],
            ['NAME'=>'RADICADO DE CUENTA', 'DIAS'=>'', 'CLASE'=>''],
            ['NAME'=>'DEVOLUCION DE CUENTA', 'DIAS'=>'', 'CLASE'=>''],
            ['NAME'=>'INCAPACIDAD', 'DIAS'=>'', 'CLASE'=>''],
            ['NAME'=>'AUDIENCIA', 'DIAS'=>'', 'CLASE'=>''],
            ['NAME'=>'CITACION', 'DIAS'=>'', 'CLASE'=>''],
            ['NAME'=>'', 'DIAS'=>'', 'CLASE'=>''],
            ['NAME'=>'', 'DIAS'=>'', 'CLASE'=>''],
            ['NAME'=>'', 'DIAS'=>'', 'CLASE'=>''],
            ['NAME'=>'', 'DIAS'=>'', 'CLASE'=>''],
            ['NAME'=>'', 'DIAS'=>'', 'CLASE'=>''],
        ];

        $limit = count($tipos);
        for($i=0;$i<$limit;$i++){
            DB::table('pqr_tipo_oficio')->insert([
                'name' => $tipos[$i],
                'created_at' => date('Y-m-d H:i:s'),
            ]);
        }
    }
}
