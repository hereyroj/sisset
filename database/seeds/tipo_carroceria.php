<?php

use Illuminate\Database\Seeder;

class tipo_carroceria_seed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $carrocerias = ['SEDAN', 'COUPE', 'BUGGY', 'CONVERTIBLE', 'LIMOSINA', 'STATION WAGON', 'HATCHBACK', 'ESCALERA', 'CERRADO(A)', 'ARTICULADO', 'BIARTICULADO', 'ESTACAS', 'FURGÓN', 'TANQUE', 'GRÚA', 'PLANCHÓN - PLATAFORMA',
            'COMPACTADOR', 'RECOLECTOR', 'ESTIBAS', 'PORTACONTENEDOR', 'BOMBA DE CONCRETO', 'CASA RODANTE', 'TOLVA', 'NIÑERA', 'BOMBEROS', 'BARREDORA', 'MIXER', 'VACTOR', 'TALADRO', 'CANERO', 'PANEL', 'VAN', 'PICO', 'DOBLE CABINA', 'PICO CERRADA', 'DOBLE CABINA CERRADA', 'PLATÓN', 'SIN CARROCERIA', 'AMBULACIA', 'CABINADO', 'CARPADO', 'WAGON', 'CROSS', 'TURISMO', 'SCOOTER', 'CUSTOM'
        ];

        $limit = count($carrocerias);
        for($i=0;$i<$limit;$i++){
            DB::table('vehiculo_carroceria')->insert([
                'name' => $carrocerias[$i],
                'created_at' => date('Y-m-d H:i:s'),
            ]);
        }
    }
}
