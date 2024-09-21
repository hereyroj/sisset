<?php

namespace App\Imports;

use App\vehiculo;
use Maatwebsite\Excel\Concerns\ToModel;
use App\vehiculo_clase;
use App\vehiculo_carroceria;
use App\vehiculo_marca;
use App\vehiculo_linea;
use App\vehiculo_combustible;
use App\vehiculo_servicio;

class VehiculoImport implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        $clase = vehiculo_clase::firstOrCreate([
            'name' => $row[6]
        ]);

        $carroceria = vehiculo_carroceria::firstOrCreate([
            'name' => $row[7]
        ]);

        $marca = vehiculo_marca::firstOrCreate([
            'name' => $row[8]
        ]);

        $linea = vehiculo_linea::firstOrCreate([
            'nombre' => $row[9],
            'cilindraje' => $row[11],
            'vehiculo_marca_id' => $marca->id
        ]);

        $combustible = vehiculo_combustible::firstOrCreate([
            'name' => $row[10]
        ]);

        $servicio = vehiculo_servicio::firstOrCreate([
            'name' => $row[14]
        ]);

        return new Vehiculo([
            'numero_motor' => $row[2],
            'numero_chasis' => $row[3],
            'placa' => $row[0],
            'modelo' => $row[1],
            'capacidad_pasajeros' => $row[4],
            'capacidad_toneladas' => $row[5],
            'vehiculo_clase_id' => $clase->id,
            'vehiculo_carroceria_id' => $carroceria->id,
            'vehiculo_marca_id' => $marca->id,
            'vehiculo_combustible_id' => $combustible->id,
            'vehiculo_linea_id' => $linea->id,
            'color' => $row[12],
            'puertas' => $row[13],
            'cambio_servicio' => $row[15],
            'vehiculo_servicio_id' => $servicio->id,
            'vehiculo_bateria_tipo_id' => null,
            'bateria_capacidad_watts' => null
        ]);
    }
}
