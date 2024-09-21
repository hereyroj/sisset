<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class SustratosConsumidos implements FromView
{
    private $sustratos;

    public function __construct($sustratos)
    {
        $this->sustratos = $sustratos;
    }

    public function view(): View
    {
        return view('admin.tramites.sustratos.listadoSustratosConsumidos', [
            'sustratos' => $this->sustratos
        ]);
    }
}
