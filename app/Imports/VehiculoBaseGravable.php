<?php

namespace App\Imports;

use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use App\vehiculo_liq_base_gravable;
use App\vehiculo_clase;
use App\vehiculo_marca;
use App\vehiculo_linea;
use Illuminate\Support\Arr;

class VehiculoBaseGravable implements ToModel, WithChunkReading
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {        
        if($row[0] == 'VIGENCIA'){
            return null;
        }

        $clase = $row[1];
        $marca = $row[2];
        $linea = $row[3];
        $cilindraje = $row[4];
        $requiereLetra = 'no';
        $baseGravable = null;

        $marca = vehiculo_marca::firstOrCreate([
            'name' => $marca
        ]);

        $headers = Arr::flatten(\anlutro\LaravelSettings\Facade::get('importHeaders'));  

        $limite = count($headers)-1;

        foreach (explode(',', $row[1]) as $rowClase) {
            if ($rowClase == 'MOTOCICLETA') {
                $requiereLetra = 'SI';
            }

            if($rowClase != null){
                $clase = vehiculo_clase::firstOrCreate([
                    'name' => $rowClase,
                    'required_letter' => $requiereLetra,
                    'pre_asignable' => 'SI',
                ]);

                $clase->hasMarcas()->attach($marca);
            }
        }    

        if(strpos($linea, 'LINEAS') !== false){            
            return new vehiculo_liq_base_gravable([
                'vehiculo_linea_id' => null,
                'modelo' => null,
                'vigencia' => $row[0],
                'avaluo' => null,
                'grupo' => $row[$limite],
                'tonelaje' => $row[5],
                'pasaje' => $row[6],
                'otro' => $row[7],
                'descripcion' => $linea,
                'vehiculo_marca_id' => $marca->id
            ]);
        }else{
            $linea = vehiculo_linea::firstOrCreate([
                'nombre' => $row[3],
                'cilindraje' => $cilindraje,
                'vehiculo_marca_id' => $marca->id,
                'watts' => $row[$limite]
            ]);        

            for($i=7; $i<$limite; $i++){
                if($row[$i] != null){
                    $baseGravable = vehiculo_liq_base_gravable::create([
                        'modelo' => $headers[$i],
                        'vigencia' => $row[0],
                        'avaluo' => $row[$i],
                        'vehiculo_linea_id' => $linea->id,
                        'grupo' => $row[$limite-1],
                        'tonelaje' => $row[5],
                        'pasaje' => $row[6],
                        'otro' => null,
                        'descripcion' => null,
                        'vehiculo_marca_id' => $marca->id
                    ]);
                }                
            }

            return $baseGravable;
        }        
    }

    public function chunkSize(): int
    {
        return 1000;
    }
}
