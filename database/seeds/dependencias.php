<?php

use Illuminate\Database\Seeder;

class dependencias extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $dependencias = ['Archivo', 'Secretaría', 'Cobro Coactivo', 'Jurídica y contratación', 'Financiera', 'Sistemas', 'Almacen', 'Inspección', 'Asesor operativo', 'Cuerpo operativo', 'Trámites'];

        $limit = count($dependencias);
        for($i=0;$i<$limit;$i++){
            DB::table('dependencia')->insert([
                'name' => $dependencias[$i],
                'created_at' => date('Y-m-d H:i:s'),
            ]);
        }
    }
}
