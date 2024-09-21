<?php

namespace App\Imports;

use App\vehiculo_liquidacion_vigencia;
use Maatwebsite\Excel\Concerns\ToModel;

class VehiculoLiquidacionVigenciaImport implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new vehiculo_liquidacion_vigencia([
            'vigencia' => $row[0],
            'impuesto_publico' => $row[1],
            'cantidad_meses_intereses' => $row[2],
            'derechos_entidad' => $row[3]
        ]);
    }
}