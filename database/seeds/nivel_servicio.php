<?php

use Illuminate\Database\Seeder;

class nivel_servicio_seed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $niveles = ['INDIVIDUAL', 'COLECTIVO'];

        $limit = count($niveles);
        for($i=0;$i<$limit;$i++){
            DB::table('vehiculo_nivel_servicio')->insert([
                'name' => $niveles[$i],
                'created_at' => date('Y-m-d H:i:s'),
            ]);
        }
    }
}
