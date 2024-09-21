<?php

use Illuminate\Database\Seeder;

class letras_terminacion extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for($i=65; $i<=90; $i++) {
            DB::table('vehiculo_clase_letra_terminacion')->insert([
                'name' => chr($i),
                'created_at' => date('Y-m-d H:i:s'),
            ]);
        }
    }
}
