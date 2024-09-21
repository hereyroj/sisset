<?php

use Illuminate\Database\Seeder;

class clase_combustible_seed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $carrocerias = ['GASOLINA', 'DIESEL', 'GAS', 'GASOLINA/GAS'];

        $limit = count($carrocerias);
        for($i=0;$i<$limit;$i++){
            DB::table('vehiculo_combustible')->insert([
                'name' => $carrocerias[$i],
                'created_at' => date('Y-m-d H:i:s'),
            ]);
        }
    }
}
