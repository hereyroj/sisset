<?php

use Illuminate\Database\Seeder;

class radio_operacion_seed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $radios = ['URBANO', 'RURAL', 'VEREDAL'];

        $limit = count($radios);
        for($i=0;$i<$limit;$i++){
            DB::table('vehiculo_radio_operacion')->insert([
                'name' => $radios[$i],
                'created_at' => date('Y-m-d H:i:s'),
            ]);
        }
    }
}
