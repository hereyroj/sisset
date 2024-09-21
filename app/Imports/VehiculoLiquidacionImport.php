<?php

namespace App\Imports;

use App\vehiculo_liquidacion;
use App\vehiculo;
use Maatwebsite\Excel\Concerns\ToModel;

class VehiculoLiquidacionImport implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        $vehiculo = vehiculo::where('placa', $row[0])->first();
        $vigencia = vehiculo_vigencia::where('vigencia',$row[1])->first();
        return new vehiculo_liquidacion([
            'valor_total' => $row[3],
            'valor_mora_total' => $row[4],
            'valor_descuento_total' => null,
            'fecha_vencimiento' => $row[7],
            'vehiculo_liq_vig_id' => $vigencia->id,
            'vehiculo_id' => $vehiculo->id,
            'valor_impuesto' => $row[8],
            'valor_avaluo' => 0,
            'codigo' => $row[2],
            'derechos_entidad' => $row[10]
        ]);
    }
}
