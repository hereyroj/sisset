<?php

use Illuminate\Database\Seeder;

class tipo_vehiculo_seed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $tipos = ['AUTOMÓVIL', 'BUS', 'BUSETA', 'CAMIÓN', 'CAMPERO', 'MICROBÚS', 'TRACTO CAMIÓN', 'MOTOCICLETA', 'MOTOCARRO', 'MOTO TRICICLO', 'CUATRIMOTO', 'VOLQUETA'];

        $limit = count($tipos);
        for($i=0;$i<$limit;$i++){
            DB::table('vehiculo_clase')->insert([
                'name' => $tipos[$i],
                'created_at' => date('Y-m-d H:i:s'),
            ]);
        }
    }
}
