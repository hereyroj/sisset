<?php

namespace App\Exports;

use App\archivo_carpeta;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use App\archivo_solicitud;

class HistorialCarpeta implements FromView
{
    private $idCarpeta;

    public function __construct($idCarpeta)
    {
        $this->idCarpeta = $idCarpeta;
    }

    public function view() : View
    {
        $id = $this->idCarpeta;
        $historial_carpeta = archivo_solicitud::with('hasCarpetaPrestada', 'hasTramiteServicio', 'hasTramiteServicio.hasFuncionario', 'hasCarpetaPrestada.hasFuncionarioAutoriza', 'hasCarpetaPrestada.hasFuncionarioEntrega', 'hasCarpetaPrestada.hasFuncionarioRecibe')->whereHas('hasCarpetaPrestada', function ($query) use ($id) {
            $query->archivo_carpeta_id = $id;
        })->get();
        return view('admin.archivo.historialCarpeta', [
            'historiales' => $historial_carpeta
        ]);
    }
}
