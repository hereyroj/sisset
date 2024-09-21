<?php

use Illuminate\Database\Seeder;
use App\usuario_tipo_documento;

class tipo_documento extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $tipos = [
            'CARNET DIPLOMATICO',
            'CEDULA DE CIUDADANIA',
            'CEDULA DE EXTRANJERIA',
            'DOCUMENTO EXTRANJERO',
            'MENOR SIN IDENTIFICAR',
            'NO DEFINIDO',
            'NUMERO IDENTIFICACION TRIBUTARIA',
            'OTRO DOCUMENTO',
            'PASAPORTE',
            'TARJETA DE EXTRANJERIA',
            'TARJETA DE IDENTIDAD'
        ];

        foreach ($tipos as $tipo){
            usuario_tipo_documento::create([
                'name' => $tipo,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ]);
        }
    }
}
