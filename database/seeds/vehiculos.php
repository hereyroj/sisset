<?php

use Illuminate\Database\Seeder;

class vehiculos extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Eloquent::unguard();
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        \DB::table('vehiculo_empresa_transporte')->truncate();
        \DB::table('vehiculo')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $tarjetas = App\tarjeta_operacion::withTrashed()->orderBy('id', 'asc')->get();
        $success = true;
        try {
            \DB::beginTransaction();
            foreach ($tarjetas as $tarjeta) {
                $vehiculo = App\vehiculo::where('placa', $tarjeta->placa)->first();
                if($vehiculo != null){
                    $vehiculo->numero_motor = $tarjeta->numero_motor;
                    $vehiculo->numero_chasis = null;
                    $vehiculo->placa = $tarjeta->placa;
                    $vehiculo->modelo = $tarjeta->modelo;
                    $vehiculo->capacidad_pasajeros = $tarjeta->capacidad_pasajeros;
                    $vehiculo->capacidad_toneladas = $tarjeta->capacidad_toneladas;
                    $vehiculo->vehiculo_clase_id = $tarjeta->tipo_vehiculo_id;
                    $vehiculo->vehiculo_carroceria_id = $tarjeta->tipo_carroceria_id;
                    $vehiculo->vehiculo_marca_id = $tarjeta->marca_vehiculo_id;
                    $vehiculo->vehiculo_combustible_id = $tarjeta->clase_combustible_id;
                    $vehiculo->save();
                }else{
                    $vehiculo = \App\vehiculo::create([
                        'numero_motor' => $tarjeta->numero_motor,
                        'numero_chasis' => null,
                        'placa' => $tarjeta->placa,
                        'modelo' => $tarjeta->modelo,
                        'capacidad_pasajeros' => $tarjeta->capacidad_pasajeros,
                        'capacidad_toneladas' => $tarjeta->capacidad_toneladas,
                        'vehiculo_clase_id' => $tarjeta->tipo_vehiculo_id,
                        'vehiculo_carroceria_id' => $tarjeta->tipo_carroceria_id,
                        'vehiculo_marca_id' => $tarjeta->marca_vehiculo_id,
                        'vehiculo_combustible_id' => $tarjeta->clase_combustible_id,
                        'created_at' => $tarjeta->created_at,
                        'updated_at' => $tarjeta->created_at
                    ]);
                }
                $success = $vehiculo->vincularEmpresa($tarjeta);
                $tarjeta->vehiculo_id = $vehiculo->id;
                $tarjeta->save();
            }
        } catch (\Exception $e) {
            $success = false;
        }

        if($success === true){
            \DB::commit();
            echo 'Vehículos creados y vinculados.';
        }else{
            \DB::rollBack();
            echo 'Error en el proceso.';
        }
    }
}
