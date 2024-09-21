<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\calendario;
use Validator;
use Storage;
use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon;
use Exception;

class CalendarioController extends Controller
{
    public function administrar()
    {
        $years = $this->getYearsDropdown();
        $months = $this->getMonthsDropdown();
        return view('admin.sistema.calendario.administrar', ['years'=>$years, 'months'=>$months]);
    }

    private function getYearsDropdown()
    {
        $current_year = date('Y');
        $old_year = Carbon::createFromFormat('Y', $current_year)->subYears(10)->format('Y');
        $future_year = Carbon::createFromFormat('Y', $current_year)->addYears(10)->format('Y');
        $years = [];
        for ($i = $old_year; $i<= $future_year; $i++){
            array_push($years, $i);
        }
        return $years;
    }

    private function getMonthsDropdown()
    {
        $months = [
            '01' => 'Enero',
            '02' => 'Febrero',
            '03' => 'Marzo',
            '04' => 'Abril',
            '05' => 'Mayo',
            '06' => 'Junio',
            '07' => 'Julio',
            '08' => 'Agosto',
            '09' => 'Septiembre',
            '10' => 'Octubre',
            '11' => 'Noviembre',
            '12' => 'Diciembre'
        ];

        return $months;
    }

    public function obtenerRegistros($year, $month)
    {
        if(!isset($month)){
            $month = date('m');
        }
        if(!isset($year)){
            $year = date('Y');
        }
        $registros = calendario::whereYear('fecha', $year)->whereMonth('fecha', $month)->paginate(31);
        return view('admin.sistema.calendario.listadoRegistros', ['registros'=>$registros])->render();
    }

    public function importar(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'archivo' => 'mimetypes:text/plain,text/csv,application/vnd.ms-excel|required',
        ], [
            'required' => 'No se ha suministrado un archivo de registros.',
            'mimetypes' => 'El archivo de importación no tiene un formato válido.',
        ]);

        if ($validator->fails()) {
            return redirect()->to('admin/sistema/calendario/administrar')->withErrors($validator->errors()->all());
        } else {
            try {
                $ruta_archivo = 'fechasImportadas-'.date('Y-m-d').'_'.date('H').'_'.date('i').'_'.date('s').'_'.auth()->user()->id.'.xls';
                Storage::disk('imports')->putFileAs('Calendario', $request->file('archivo'), $ruta_archivo);

                Excel::filter('chunk')->load(storage_path('app/imports/calendario/'.$ruta_archivo), false, 'ISO-8859-1')->chunk(250, function (
                    $results
                ) {
                    foreach ($results as $fila) {
                        calendario::updateOrcreate([
                            'dia' => $fila->dia,
                            'fecha' => Carbon::createFromFormat('d-m-Y', $fila->fecha)->toDateString(),
                            'laboral' => $fila->laborable,
                            'feriado' => $fila->feriado,
                            'fin_de_semana' => $fila->findesemana,
                            'descripcion' => $fila->descripcion,
                        ]);
                    }
                });

                return redirect()->to('admin/sistema/calendario/administrar')->with('mensaje', 'Se ha importado el calendario');
            } catch (Exception $e) {
                return redirect()->to('admin/sistema/calendario/administrar')->withErrors(['Ha ocurrido un error en el proceso. Por favor intente nuevamente o comuníquese con un administrador.']);
            }
        }
    }
}
