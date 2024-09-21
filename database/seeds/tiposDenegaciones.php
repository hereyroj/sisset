<?php

use Illuminate\Database\Seeder;

class tiposDenegaciones extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $denegaciones = [
            'No encontrada',
            'Restringida',
            'En mal estado',
            'No disponible'
        ];

        $limit = count($denegaciones);
        for($i=0;$i<$limit;$i++){
            DB::table('archivo_denegacion')->insert([
                'name' => $denegaciones[$i],
                'created_at' => date('Y-m-d H:i:s'),
            ]);
        }
    }
}
