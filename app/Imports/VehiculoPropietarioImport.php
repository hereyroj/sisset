<?php

namespace App\Imports;

use App\vehiculo_propietario;
use App\usuario_tipo_documento;
use Maatwebsite\Excel\Concerns\ToModel;

class VehiculoPropietarioImport implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        $vehiculo = vehiculo::where('placa', $row[0])->first();
        $tipoDocumento = usuario_tipo_documento::where('name',$row[1])->first();
        $departamento = departamento::where('name',$row[7])->select('id')->first();
        $municipio = ciudad::where('name',$row[8])->select('id')->first();
        return new vehiculo_propietario([
            'nombre' => $row[3],
            'numero_documento' => $row[2],
            'tipo_documento_id' => $tipoDocumento->id,
            'telefono' => $row[4],
            'departamento_id' => $departamento,
            'municipio_id' => $municipio,
            'direccion' => $row[6],
            'correo_electronico' => $row[5]
        ]);
    }
}
