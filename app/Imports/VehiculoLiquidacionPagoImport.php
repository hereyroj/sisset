<?php

namespace App\Imports;

use App\vehiculo_liquidacion_pago;
use App\vehiculo_liquidacion;
use App\vehiculo;
use Maatwebsite\Excel\Concerns\ToModel;

class VehiculoLiquidacionPagoImport implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        $liquidacion = vehiculo_liquidacion::whereHas('hasVehiculo', function($query) use ($row){
            $query->where('placa', $row[0]);        
        })->whereHas('hasVigencia', function($query2) use ($row){
            $query2->where('vigencia', $row[1]);
        })->first();
        return new vehiculo_liquidacion_pago([
            'vehiculo_liquidacion_id' => $liquidacion->id,
            'numero_consignacion' => $row[2],
            'valor_consignacion' => $row[3],
            'consignacion' => null
        ]);
    }
}
