<?php

namespace App\Exports;

use App\gd_pqr;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class PqrExports implements FromView
{
    private $fecha_inicio, $fecha_fin, $tipoPQR;

    public function __construct($fecha_inicio, $fecha_fin, $tipoPQR)
    {
        $this->fecha_inicio = $fecha_inicio; 
        $this->fecha_fin = $fecha_fin; 
        $this->tipoPQR = $tipoPQR;
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function view(): View
    {
        $pqrs = gd_pqr::whereDate('created_at', '>=', $this->fecha_inicio)->whereDate('created_at', '<=', $this->fecha_fin)->where('tipo_pqr', $this->tipoPQR)->get();
        if($this->tipoPQR === 'CoEx'){
            return view('admin.exports.pqr.controlInterno.coex', ['pqrs' => $pqrs]);
        }elseif($this->tipoPQR === 'CoIn'){
            return view('admin.exports.pqr.controlInterno.coin', ['pqrs' => $pqrs]);
        }elseif($this->tipoPQR === 'CoSa'){
            return view('admin.exports.pqr.controlInterno.cosa', ['pqrs' => $pqrs]);
        }else{

        }
    }
}
