<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class PlacasPedidas implements FromView
{
    private $servicios;

    public function __construct($servicios)
    {
        $this->servicios = $servicios;
    }

    public function view(): View
    {
        return view('admin.tramites.placa.listadoPlacasPedidas', [
            'servicios' => $this->servicios
        ]);
    }
}
