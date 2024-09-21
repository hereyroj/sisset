<?php

use Illuminate\Database\Seeder;

class marca_vehiculo_seed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $marcas = ['RENAULT', 'CHEVROLET', 'KIA', 'MAZDA', 'MERCEDEZ-BENZ', 'LAND ROVERT', 'PEUGEOT', 'CITROËN', 'AUDI', 'BMW', 'DAIHATSU', 'DODGE', 'FIAT', 'FORD', 'HONDA', 'HYUNDAI', 'JEEP', 'MINI',
            'MITSUBISHI', 'NISSAN', 'PORSCHE', 'SEAT', 'SKODA', 'SSANGYONG', 'SUBARU', 'TOYOTA', 'VOLKSWAGEN'];

        $limit = count($marcas);
        for($i=0;$i<$limit;$i++){
            DB::table('vehiculo_marca')->insert([
                'name' => $marcas[$i],
                'created_at' => date('Y-m-d H:i:s'),
            ]);
        }
    }
}
