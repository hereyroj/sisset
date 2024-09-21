<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \Spatie\Activitylog\Models\Activity;
use \App\Error;

class LogController extends Controller
{
    public function monitor()
    {
        $filtrosActividades = [
            '1' => 'Numero documento',
            '2' => 'Radicado entrada',
            '3' => 'Radicado salida',
            '4' => 'Consecutivo',
        ];
        $sFiltroActividades = null;

        $filtrosExcepciones = [
            '1' => 'Numero documento',
            '2' => 'Radicado entrada',
            '3' => 'Radicado salida',
            '4' => 'Consecutivo',
        ];
        $sFiltroExcepciones = null;

        return view('admin.sistema.log.monitor', [
            'filtrosActividades' => $filtrosActividades,
            'sFiltroActividades' => $sFiltroActividades,
            'filtrosExcepciones' => $filtrosExcepciones,
            'sFiltroExcepciones' => $sFiltroExcepciones,
        ]);
    }

    public function obtenerLogsActividad()
    {
        $logs = Activity::orderBy('created_at', 'desc')->paginate(100);

        return view('admin.sistema.log.logsActividades', ['logs' => $logs])->render();
    }

    public function obtenerCambiosActividad($id)
    {
        $actividad = Activity::find($id);

        return view('admin.sistema.log.cambiosActividad', ['actividad' => $actividad])->render();
    }

    public function obtenerLogsExcepciones()
    {
        $logs = Error::with('user')->orderBy('created_at', 'desc')->paginate(100);

        return view('admin.sistema.log.logsExcepciones', ['excepciones' => $logs])->render();
    }
}
