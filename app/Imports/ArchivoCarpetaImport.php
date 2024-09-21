<?php

namespace App\Imports;

use App\archivo_carpeta;
use App\archivo_carpeta_estado;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ArchivoCarpetaImport implements ToModel, WithBatchInserts , WithChunkReading, SkipsOnError, WithHeadingRow
{
    use Importable, SkipsErrors;
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        $estado = archivo_carpeta_estado::find($row['estado']);
        if(strlen($row['placa']) >= 6){
            return new archivo_carpeta([
                'name' => $row['placa'],
                'available' => $estado->estado_carpeta,
                'archivo_carpeta_estado_id' => $estado->id,
                'vehiculo_clase_id' => $row['clase'],
                'radicado' => $row['radicado'],
                'vehiculo_servicio_id' => $row['servicio'],
            ]);
        }else{
            return null;
        }
    }

    public function batchSize(): int
    {
        return 500;
    }

    public function chunkSize(): int
    {
        return 500;
    }
}
