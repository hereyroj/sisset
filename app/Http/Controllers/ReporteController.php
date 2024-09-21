<?php

namespace App\Http\Controllers;

use anlutro\LaravelSettings\Facade as Setting;
use Carbon\Carbon;
use App\archivo_carpeta;
use App\archivo_carpeta_estado;
use App\archivo_solicitud;
use App\archivo_solicitud_de_mo;
use App\archivo_solicitud_va_ve;
use App\comparendo;
use App\comparendo_tipo;
use App\dependencia;
use App\empresa_transporte;
use App\gd_pqr;
use App\gd_pqr_clase;
use App\sancion_tipo;
use App\placa;
use App\Role;
use App\sustrato;
use App\tarjeta_operacion;
use App\tipo_sustrato;
use App\tramite;
use App\tramite_servicio_estado;
use App\tramite_solicitud;
use App\tramite_solicitud_origen;
use App\tramite_solicitud_turno;
use App\User;
use App\vehiculo_clase;
use App\vehiculo_nivel_servicio;
use App\vehiculo_servicio;
use App\gd_medio_traslado;
use App\vehiculo_marca;
use App\vehiculo_combustible;
use App\vehiculo_carroceria;
use App\Exports\PqrExports;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;
use Validator;
use Illuminate\Validation\Rule;
use App\Charts\ChartSolicitudesTramitesPorAñosYMeses;
use App\Charts\ChartSolicitudesTramitesPorDias;
use App\Charts\ChartSolicitudesTramitesPorTramites;     

class ReporteController extends Controller
{
    public function archivo_SeriesRegistradas()
    {
        $series = archivo_carpeta::all()->groupBy(function ($item, $key) {
            return substr($item->name, 0, -3);
        })->count();

        return view('admin.reportes.counter', ['number' => $series, 'title' => 'Total series registradas']);
    }

    public function archivo_CarpetasTotales()
    {
        $carpetas = archivo_carpeta::count();

        return view('admin.reportes.counter', ['number' => $carpetas, 'title' => 'Total carpetas registradas']);
    }

    public function archivo_CarpetasPorEstado()
    {
        $estados_carpeta = archivo_carpeta_estado::all();
        if ($estados_carpeta != null) {
            $values = [];

            foreach ($estados_carpeta as $estado) {
                array_push($values, $estado->hasCarpetas()->count());
            }

            $carpetas = Charts::database(archivo_carpeta::all(), 'bar', 'highcharts')->title('Total de carpetas por estado')->labels($estados_carpeta->pluck('name'))->values($values)->dimensions(500, 500)->responsive(true)->elementLabel('Cantidad');

            return view('admin.reportes.layout', ['Chart' => $carpetas]);
        } else {
            return null;
        }
    }

    public function archivo_CarpetasPorClaseVehiculo()
    {
        $clases_vehiculo = vehiculo_clase::all();
        if ($clases_vehiculo != null) {
            $values = [];

            foreach ($clases_vehiculo as $clase) {
                array_push($values, $clase->hasCarpetas()->count());
            }

            $carpetas = Charts::database(archivo_carpeta::all(), 'bar', 'highcharts')->title('Total de carpetas por clase de vehiculo')->labels($clases_vehiculo->pluck('name'))->values($values)->dimensions(500, 500)->responsive(true)->elementLabel('Cantidad');

            return view('admin.reportes.layout', ['Chart' => $carpetas]);
        } else {
            return null;
        }
    }

    public function archivo_CarpetasPorFuera()
    {
        $total = archivo_solicitud::whereHas('hasCarpetaPrestada', function ($q){
           $q->where('fecha_devolucion', null);
        })->count();

        return view('admin.reportes.counter', ['number' => $total, 'title' => 'Total carpetas por fuera']);
    }

    public function archivo_SolicitudesPorDependencia()
    {
        $dependencias = dependencia::all();

        if ($dependencias != null) {
            $values = [];

            foreach ($dependencias as $dependencia) {
                array_push($values, $this->countSolicitudesPorDependencia($dependencia->id));
            }

            $reporteSolicitudesPorDependencia = Charts::create('pie', 'highcharts')->title('Solicitudes por dependencia')->labels($dependencias->pluck('name'))->values($values)->dimensions(500, 500)->responsive(true)->elementLabel('Cantidad');

            return view('admin.reportes.layout', ['Chart' => $reporteSolicitudesPorDependencia]);
        } else {
            return null;
        }
    }

    public function countSolicitudesPorDependencia($dependenciaId)
    {
        return archivo_solicitud::whereHas('hasCarpetaPrestada',function($query)use($dependenciaId){
            $query->whereHas('hasFuncionarioRecibe', function($query2)use($dependenciaId){
                $query2->where('dependencia_id',$dependenciaId);
            });
        })->count();
    }

    public function archivo_SolicitudesPorTramites()
    {
        $tramites = Tramite::all();

        if ($tramites != null) {
            $values = [];

            foreach ($tramites as $tramite) {
                array_push($values, $this->countSolicitudesPorTramites($tramite->id));
            }

            $reporteSolicitudesPorTramites = Charts::create('pie', 'highcharts')->title('Solicitudes por tramites')->labels($tramites->pluck('name'))->values($values)->dimensions(500, 500)->responsive(true)->elementLabel('Cantidad');

            return view('admin.reportes.layout', ['Chart' => $reporteSolicitudesPorTramites]);
        } else {
            return null;
        }
    }

    public function countSolicitudesPorTramites($tramiteId)
    {
        $tramites = archivo_solicitud::whereHas('hasTramiteServicio', function ($q) use ($tramiteId) {
            $q->whereHas('hasSolicitud', function ($y) use ($tramiteId){
                $y->whereHas('hasTramites', function ($z) use ($tramiteId){
                    $z->where('id', '=', $tramiteId);
                });
            });
        })->get();

        return $tramites->count();
    }

    public function archivo_SolicitudesPorMeses()
    {
        $historicoSolicitudesPorMeses = Charts::database(archivo_solicitud::all(), 'bar', 'highcharts')->elementLabel("Cantidad")->title('Solicitudes por meses')->dimensions(1000, 500)->responsive(true)->groupByMonth(Setting::get('vigencia'), true);

        return view('admin.reportes.layout', ['Chart' => $historicoSolicitudesPorMeses]);
    }

    public function archivo_SolicitudesPorDias()
    {
        $historicoSolicitudesPorDias = Charts::database(archivo_solicitud::all(), 'bar', 'highcharts')->elementLabel("Cantidad")->title('Solicitudes por dias')->dimensions(1000, 500)->responsive(true)->groupByDay();

        return view('admin.reportes.layout', ['Chart' => $historicoSolicitudesPorDias]);
    }

    public function archivo_CarpetasPorMeses()
    {
        $historicoCarpetasPorMeses = Charts::database(archivo_solicitud::where('archivo_carpeta_prestamo_id', '!=', null)->get(), 'bar', 'highcharts')->elementLabel("Cantidad")->title('Carpetas entregadas por meses')->dimensions(1000, 500)->responsive(true)->groupByMonth(Setting::get('vigencia'), true);

        return view('admin.reportes.layout', ['Chart' => $historicoCarpetasPorMeses]);
    }

    public function archivo_CarpetasPorDias()
    {
        $historicoCarpetasPorDias = Charts::database(archivo_solicitud::where('archivo_carpeta_prestamo_id', '!=', null)->get(), 'bar', 'highcharts')->elementLabel("Cantidad")->title('Carpetas entregadas por dias')->dimensions(1000, 500)->responsive(true)->groupByDay();

        return view('admin.reportes.layout', ['Chart' => $historicoCarpetasPorDias]);
    }

    public function sistema_ultimosUsuariosCreados()
    {
        $historicoCreacionUsuarios = Charts::database(User::all(), 'bar', 'highcharts')->elementLabel("Cantidad")->dimensions(1000, 500)->responsive(true)->title('Histórico creación de usuarios')->groupByMonth(Setting::get('vigencia'), true);

        return view('admin.reportes.layout', ['Chart' => $historicoCreacionUsuarios]);
    }

    public function inspeccion_TOExpedidasActualVigencia()
    {
        $historicoTOPresenteAño = Charts::database(tarjeta_operacion::all(), 'line', 'highcharts')->elementLabel("Cantidad")->dimensions(1000, 500)->responsive(true)->title('Histórico expendición de Tarjetas de Operación')->groupByMonth(Setting::get('vigencia'), true);

        return view('admin.reportes.layout', ['Chart' => $historicoTOPresenteAño]);
    }

    public function inspeccion_TOActivasPorEmpresa()
    {
        $empresas = empresa_transporte::all();
        if ($empresas != null) {
            $values = [];

            foreach ($empresas as $empresa) {
                array_push($values, $empresa->hasManyTOS()->count());
            }

            $tosActivasPorEmpresa = Charts::database(tarjeta_operacion::all(), 'pie', 'highcharts')->elementLabel("Cantidad")->labels($empresas->pluck('name'))->dimensions(1000, 500)->responsive(true)->values($values)->title('Tarjetas de operación activas por empresa');

            return view('admin.reportes.layout', ['Chart' => $tosActivasPorEmpresa]);
        } else {
            return null;
        }
    }

    public function inspeccion_TOPorNivelDeServicio()
    {
        $niveles_servicio = vehiculo_nivel_servicio::all();
        if ($niveles_servicio != null) {
            $values = [];

            foreach ($niveles_servicio as $nivel_servicio) {
                array_push($values, $nivel_servicio->hasManyTOS()->count());
            }

            $tosPorNivelesDeServicio = Charts::database(tarjeta_operacion::all(), 'pie', 'highcharts')->elementLabel("Cantidad")->labels($niveles_servicio->pluck('name'))->dimensions(1000, 500)->responsive(true)->values($values)->title('Tarjetas de operación por nivel de servicio');

            return view('admin.reportes.layout', ['Chart' => $tosPorNivelesDeServicio]);
        } else {
            return null;
        }
    }

    public function inspeccion_TOSVencidas()
    {
        $tosVencidas = tarjeta_operacion::with('hasEmpresaTransporte')->where('fecha_vencimiento', '<', date('Y-m-d'))->whereNotIn('placa', function (
                $query
            ) {
                $query->select('placa')->from('tarjeta_operacion')->whereDate('fecha_vencimiento', '>', date('Y-m-d'));
            })->orderBy('created_at')->get();
        /*
        Preparamos el array para la tabla
         */
        $data = [];
        $i = 0;
        foreach ($tosVencidas as $tos) {
            $data[$i] = [
                $tos->fecha_vencimiento,
                $tos->placa,
                $tos->hasEmpresaTransporte->name,
                '<a href="#" class="btn btn-secondary" onclick="event.preventDefault();verTO('.$tos->id.');"><span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span> Ver</a>',
            ];
            $i++;
        }

        return view('admin.reportes.list', [
            'data' => $data,
            'columns' => ['Vencimiento', 'Placa', 'Empresa Transporte', 'Acción'],
            'title' => 'Tarjetas de Operacion Vencidas',
        ])->render();
    }

    public function inspeccion_TOSProximasAVencer()
    {
        $tosProximasAVencer = tarjeta_operacion::with('hasEmpresaTransporte')->whereBetween('fecha_vencimiento', [
                Carbon::now(),
                Carbon::now()->addMonths(3)->toDateString(),
            ])->whereNotIn('placa', function ($query) {
                $query->select('placa')->from('tarjeta_operacion')->whereDate('fecha_vencimiento', '>', Carbon::now()->addMonths(3)->toDateString());
            })->orderBy('created_at')->get();
        /*
        Preparamos el array para la tabla
         */
        $data = [];
        $i = 0;
        foreach ($tosProximasAVencer as $tos) {
            $data[$i] = [
                $tos->fecha_vencimiento,
                $tos->placa,
                $tos->hasEmpresaTransporte->name,
                '<a href="#" class="btn btn-secondary" onclick="event.preventDefault();verTO('.$tos->id.');"><span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span> Ver</a>',
            ];
            $i++;
        }

        return view('admin.reportes.list', [
            'data' => $data,
            'columns' => ['Vencimiento', 'Placa', 'Empresa Transporte', 'Acción'],
            'title' => 'Tarjetas de Operacion Proximas a Vencer',
        ])->render();
    }

    public function inspeccion_ComparendosPorAñosYMeses()
    {
        $currentYear = date(Setting::get('vigencia'));
        $data = [];
        $month = [];
        for($i=$currentYear-3;$i<=$currentYear;$i++){
            for($e=1;$e<=12;$e++){
                array_push($month, \DB::table('comparendo')->whereYear('fecha_realizacion', $i)->whereMonth('fecha_realizacion', $e)->count());
            }
            array_push($data, ['year'=>$i,'data'=>$month]);
            $month = [];
        }

        $chart = Charts::multi('bar', 'highcharts')
            ->title("Sanciones por años y meses")
            ->elementLabel("Total")
            ->responsive(true)
            ->template("material")
            ->legend(true)
            ->dimensions(1000, 500)
            ->labels(['Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'])
            ->dataset($data[0]['year'], $data[0]['data'])
            ->dataset($data[1]['year'], $data[1]['data'])
            ->dataset($data[2]['year'], $data[2]['data'])
            ->dataset($data[3]['year'], $data[3]['data']);

        return view('admin.reportes.layout', ['Chart' => $chart]);
    }

    public function inspeccion_ComparendosPorTipos()
    {
        $tipos = comparendo_tipo::all();
        if ($tipos != null) {
            $values = [];

            foreach ($tipos as $tipo) {
                array_push($values, $tipo->hasComparendos()->count());
            }

            $comparendosPorTipos = Charts::database(comparendo::all(), 'pie', 'highcharts')->elementLabel("Cantidad")->labels($tipos->pluck('name'))->dimensions(1000, 500)->responsive(true)->values($values)->title('Comparendos por tipos');

            return view('admin.reportes.layout', ['Chart' => $comparendosPorTipos]);
        } else {
            return null;
        }
    }

    public function sancionesPorAñosYMeses()
    {
        $currentYear = date(Setting::get('vigencia'));
        $data = [];
        $month = [];
        for($i=$currentYear-3;$i<=$currentYear;$i++){
            for($e=1;$e<=12;$e++){
                array_push($month, \DB::table('sancion')->whereYear('fecha_publicacion', $i)->whereMonth('fecha_publicacion', $e)->count());
            }
            array_push($data, ['year'=>$i,'data'=>$month]);
            $month = [];
        }

        $chart = Charts::multi('bar', 'highcharts')
            ->title("Sanciones por años y meses")
            ->elementLabel("Total")
            ->responsive(true)
            ->template("material")
            ->legend(true)
            ->dimensions(1000, 500)
            ->labels(['Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'])
            ->dataset($data[0]['year'], $data[0]['data'])
            ->dataset($data[1]['year'], $data[1]['data'])
            ->dataset($data[2]['year'], $data[2]['data'])
            ->dataset($data[3]['year'], $data[3]['data']);

        return view('admin.reportes.layout', ['Chart' => $chart]);
    }

    public function sancionesPorTiposYAños()
    {
        $tiposSaciones = sancion_tipo::all();
        if ($tiposSaciones != null) {
            $values = [];

            foreach ($tiposSaciones as $tipo) {
                array_push($values, $tipo->hasSanciones()->count());
            }

            $sancionesPorTipo = Charts::database(sancion_tipo::all(), 'bar', 'highcharts')->elementLabel("Cantidad")->labels($tiposSaciones->pluck('name'))->dimensions(1000, 500)->responsive(true)->values($values)->title('Sanciones por Tipo');

            return view('admin.reportes.layout', ['Chart' => $sancionesPorTipo]);
        } else {
            return null;
        }
    }

    public function cobrocoactivo_ComparendosPorAñosYMeses()
    {
        $currentYear = date(Setting::get('vigencia'));
        $data = [];
        $month = [];
        for($i=$currentYear-3;$i<=$currentYear;$i++){
            for($e=1;$e<=12;$e++){
                array_push($month, \DB::table('coactivo_comparendo')->whereYear('publication_date', $i)->whereMonth('publication_date', $e)->count());
            }
            array_push($data, ['year'=>$i,'data'=>$month]);
            $month = [];
        }

        $chart = Charts::multi('bar', 'highcharts')
            ->title("Comparendos por años y meses")
            ->elementLabel("Total")
            ->responsive(true)
            ->template("material")
            ->legend(true)
            ->dimensions(1000, 500)
            ->labels(['Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'])
            ->dataset($data[0]['year'], $data[0]['data'])
            ->dataset($data[1]['year'], $data[1]['data'])
            ->dataset($data[2]['year'], $data[2]['data'])
            ->dataset($data[3]['year'], $data[3]['data']);

        return view('admin.reportes.layout', ['Chart' => $chart]);
    }

    public function cobrocoactivo_FotoMultasPorAñosYMeses()
    {
        $currentYear = date(Setting::get('vigencia'));
        $data = [];
        $month = [];
        for($i=$currentYear-3;$i<=$currentYear;$i++){
            for($e=1;$e<=12;$e++){
                array_push($month, \DB::table('coactivo_foto_multa')->whereYear('publication_date', $i)->whereMonth('publication_date', $e)->count());
            }
            array_push($data, ['year'=>$i,'data'=>$month]);
            $month = [];
        }

        $chart = Charts::multi('bar', 'highcharts')
            ->title("Fotomultas por años y meses")
            ->elementLabel("Total")
            ->responsive(true)
            ->template("material")
            ->legend(true)
            ->dimensions(1000, 500)
            ->labels(['Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'])
            ->dataset($data[0]['year'], $data[0]['data'])
            ->dataset($data[1]['year'], $data[1]['data'])
            ->dataset($data[2]['year'], $data[2]['data'])
            ->dataset($data[3]['year'], $data[3]['data']);

        return view('admin.reportes.layout', ['Chart' => $chart]);
    }

    /*
        Seccion mis solicitudes
     */
    public function solicitudes_MisSolicitudesPorMeses()
    {
        $misSolicitudes = archivo_solicitud::where('user_request_id', auth()->user()->id)->get();
        $misSolicitudes = Charts::database($misSolicitudes, 'bar', 'highcharts')->elementLabel("Total")->title('Mis solicitudes por meses')->dimensions(500, 500)->responsive(true)->groupByMonth(Setting::get('vigencia'), true);

        return view('admin.reportes.layout', ['Chart' => $misSolicitudes]);
    }

    public function solicitudes_MisSolicitudesPorDias()
    {
        $misSolicitudes = archivo_solicitud::where('user_request_id', auth()->user()->id)->get();
        $misSolicitudes = Charts::database($misSolicitudes, 'bar', 'highcharts')->elementLabel("Total")->title('Mis solicitudes por días')->dimensions(500, 500)->responsive(true)->groupByDay(date('m'), true);

        return view('admin.reportes.layout', ['Chart' => $misSolicitudes]);
    }

    public function solicitudes_MisSolicitudesUltimaSemana()
    {
        $misSolicitudes = archivo_solicitud::where('user_request_id', auth()->user()->id)->get();
        $misSolicitudes = Charts::database($misSolicitudes, 'bar', 'highcharts')->elementLabel("Total")->title('Mis solicitudes última semana')->dimensions(500, 500)->responsive(true)->lastByDay(7, true);

        return view('admin.reportes.layout', ['Chart' => $misSolicitudes]);
    }

    public function solicitudes_MisSolicitudesAprobadas()
    {
        $misSolicitudes = archivo_solicitud::doesntHave('couldHaveDenegacion')->where('created_at', '!=', null)->where('user_request_id', auth()->user()->id)->count();

        return view('admin.reportes.counter', ['number' => $misSolicitudes, 'title' => 'Mis solicitudes aprobadas']);
    }

    public function solicitudes_MisSolicitudesRechazadas()
    {
        $misSolicitudes = archivo_solicitud::where('user_request_id', auth()->user()->id)->whereHas('couldHaveDenegacion')->count();

        return view('admin.reportes.counter', ['number' => $misSolicitudes, 'title' => 'Mis solicitudes rechazadas']);
    }

    public function solicitudes_MisSolicitudesPendientes()
    {
        $misSolicitudes = archivo_solicitud::doesntHave('couldHaveDenegacion')->where('created_at', '=', null)->where('user_request_id', auth()->user()->id)->count();

        return view('admin.reportes.counter', ['number' => $misSolicitudes, 'title' => 'Mis solicitudes pendientes']);
    }

    public function solicitudes_CarpetasRecibidas()
    {
        $misSolicitudes = archivo_solicitud::where('created_at', '=', null)->where('user_delivered_id', auth()->user()->id)->count();

        return view('admin.reportes.counter', ['number' => $misSolicitudes, 'title' => 'Carpetas recibidas']);
    }

    public function solicitudes_CarpetasRecibidasPorMeses()
    {
        $misSolicitudes = archivo_solicitud::where('user_delivered_id', auth()->user()->id)->get();
        $misSolicitudes = Charts::database($misSolicitudes, 'bar', 'highcharts')->elementLabel("Total")->title('Carpetas Recibidas por Meses')->dimensions(500, 500)->responsive(true)->groupByMonth(Setting::get('vigencia'), true);

        return view('admin.reportes.layout', ['Chart' => $misSolicitudes]);
    }

    public function solicitudes_CarpetasRecibidasPorDias()
    {
        $misSolicitudes = archivo_solicitud::where('user_delivered_id', auth()->user()->id)->get();
        $misSolicitudes = Charts::database($misSolicitudes, 'bar', 'highcharts')->elementLabel("Total")->title('Carpetas Recibidas por Dias')->dimensions(500, 500)->responsive(true)->groupByDay(date('m'), true);

        return view('admin.reportes.layout', ['Chart' => $misSolicitudes]);
    }

    public function solicitudes_CarpetasRecibidasUltimaSemana()
    {
        $misSolicitudes = archivo_solicitud::where('user_delivered_id', auth()->user()->id)->get();
        $misSolicitudes = Charts::database($misSolicitudes, 'bar', 'highcharts')->elementLabel("Total")->title('Carpetas Recibidas Ultima Semana')->dimensions(500, 500)->responsive(true)->lastByDay(7, true);

        return view('admin.reportes.layout', ['Chart' => $misSolicitudes]);
    }

    public function gestionSolicitudes_SolicitudesAprobadasPorDias()
    {
        $SolicitudesAprobadasPorDias = archivo_solicitud::where('created_at', '!=', null)->get();
        $SolicitudesAprobadasPorDias = Charts::database($SolicitudesAprobadasPorDias, 'bar', 'highcharts')->elementLabel("Total")->title('Solicitudes Aprobadas por Dias')->dimensions(500, 500)->responsive(true)->groupByDay(date('m'), true);

        return view('admin.reportes.layout', ['Chart' => $SolicitudesAprobadasPorDias]);
    }

    public function gestionSolicitudes_SolicitudesAprobadasPorMeses()
    {
        $SolicitudesAprobadasPorMeses = archivo_solicitud::where('created_at', '!=', null)->get();
        $SolicitudesAprobadasPorMeses = Charts::database($SolicitudesAprobadasPorMeses, 'bar', 'highcharts')->elementLabel("Total")->title('Solicitudes Aprobadas por Meses')->dimensions(500, 500)->responsive(true)->groupByMonth(Setting::get('vigencia'), true);

        return view('admin.reportes.layout', ['Chart' => $SolicitudesAprobadasPorMeses]);
    }

    public function gestionSolicitudes_SolicitudesAprobadasUltimaSemana()
    {
        $SolicitudesAprobadasUltimaSemana = archivo_solicitud::where('created_at', '!=', null)->get();
        $SolicitudesAprobadasUltimaSemanas = Charts::database($SolicitudesAprobadasUltimaSemana, 'bar', 'highcharts')->elementLabel("Total")->title('Solicitudes Aprobadas Ultima Semana')->dimensions(500, 500)->responsive(true)->lastByDay(7, true);

        return view('admin.reportes.layout', ['Chart' => $SolicitudesAprobadasUltimaSemanas]);
    }

    public function gestionSolicitudes_SolicitudesDenegadasPorDias()
    {
        $SolicitudesDenegadasPorDias = archivo_solicitud::whereHas('hasDenegacion')->get();
        $SolicitudesDenegadasPorDias = Charts::database($SolicitudesDenegadasPorDias, 'bar', 'highcharts')->elementLabel("Total")->title('Solicitudes Denegadas por Dias')->dimensions(500, 500)->responsive(true)->groupByDay(date('m'), true);

        return view('admin.reportes.layout', ['Chart' => $SolicitudesDenegadasPorDias]);
    }

    public function gestionSolicitudes_SolicitudesDenegadasPorMeses()
    {
        $SolicitudesDenegadasPorMeses = archivo_solicitud::whereHas('hasDenegacion')->get();
        $SolicitudesDenegadasPorMeses = Charts::database($SolicitudesDenegadasPorMeses, 'bar', 'highcharts')->elementLabel("Total")->title('Solicitudes Denegadas por Meses')->dimensions(500, 500)->responsive(true)->groupByMonth(Setting::get('vigencia'), true);

        return view('admin.reportes.layout', ['Chart' => $SolicitudesDenegadasPorMeses]);
    }

    public function gestionSolicitudes_SolicitudesDenegadasUltimaSemana()
    {
        $SolicitudesDenegadasUltimaSemana = archivo_solicitud::where('created_at', '!=', null)->get();
        $SolicitudesDenegadasUltimaSemanas = Charts::database($SolicitudesDenegadasUltimaSemana, 'bar', 'highcharts')->elementLabel("Total")->title('Solicitudes Denegadas Ultima Semana')->dimensions(500, 500)->responsive(true)->lastByDay(7, true);

        return view('admin.reportes.layout', ['Chart' => $SolicitudesDenegadasUltimaSemanas]);
    }

    public function gestionSolicitudes_SolicitudesSinEntregar()
    {
        $SolicitudesSinEntregar = archivo_solicitud::whereHas('hasCarpetaPrestada', function($query) {
            $query->where('funcionario_recibe_id', null);
        })->count();

        return view('admin.reportes.counter', [
            'number' => $SolicitudesSinEntregar,
            'title' => 'Solicitudes sin entregar',
        ]);
    }

    public function gestionSolicitudes_CarpetasSinDevolver()
    {
        $CarpetasSinDevolver = archivo_solicitud::has('hasCarpetaPrestada')->whereHas('hasCarpetaPrestada', function($query){
            $query->where('fecha_devolucion', null);
        })->count();

        return view('admin.reportes.counter', ['number' => $CarpetasSinDevolver, 'title' => 'Carpetas sin devolver']);
    }

    public function gestionSolicitudes_CarpetasSinValidar()
    {
        $SolicitudesSinValidar = archivo_solicitud::has('hasCarpetaPrestada')->doesntHave('hasValidacion')->whereHas('hasCarpetaPrestada', function($query){
            $query->where('fecha_devolucion', '!=', null);
        })->count();

        return view('admin.reportes.counter', [
            'number' => $SolicitudesSinValidar,
            'title' => 'Solicitudes sin validar',
        ]);
    }

    public function gestionSolicitudes_CarpetasValidadasPorEstado()
    {
        $estadosValidacion = archivo_solicitud_va_ve::all();
        if ($estadosValidacion != null) {
            $values = [];

            foreach ($estadosValidacion as $estado) {
                array_push($values, $estado->hasSolicitudesValidadas()->count());
            }

            $solicitudesValidadasPorMotivo = Charts::database(archivo_solicitud_va_ve::all(), 'pie', 'highcharts')->elementLabel("Cantidad")->labels($estadosValidacion->pluck('name'))->dimensions(1000, 500)->responsive(true)->values($values)->title('Solicitudes Validadas por Estado');

            return view('admin.reportes.layout', ['Chart' => $solicitudesValidadasPorMotivo]);
        } else {
            return null;
        }
    }

    public function gestionSolicitudes_SolicitudesDenegadasPorMotivo()
    {
        $motivosDenegacion = archivo_solicitud_de_mo::all();
        if ($motivosDenegacion != null) {
            $values = [];

            foreach ($motivosDenegacion as $motivo) {
                array_push($values, $motivo->hasSolicitudesDenegadas()->count());
            }

            $solicitudesDenegadasPorMotivo = Charts::database(archivo_solicitud_de_mo::all(), 'pie', 'highcharts')->elementLabel("Cantidad")->labels($motivosDenegacion->pluck('name'))->dimensions(1000, 500)->responsive(true)->values($values)->title('Solicitudes Denegadas por Motivo');

            return view('admin.reportes.layout', ['Chart' => $solicitudesDenegadasPorMotivo]);
        } else {
            return null;
        }
    }

    public function usuarios_UsuariosPorRoles()
    {
        $roles = Role::with('tieneUsuarios')->get();

        if ($roles != null) {
            $values = [];

            foreach ($roles as $role) {
                array_push($values, $role->tieneUsuarios()->count());
            }

            $reporteRolesUsuarios = Charts::create('pie', 'highcharts')->title('Usuarios por roles')->labels($roles->pluck('name'))->values($values)->dimensions(500, 500)->responsive(true);

            return view('admin.reportes.layout', ['Chart' => $reporteRolesUsuarios]);
        } else {
            return null;
        }
    }

    public function usuarios_UsuariosCreadosUltimoMes()
    {
        $historicoCreacionUsuarios = Charts::database(User::all(), 'bar', 'highcharts')->elementLabel("Total")->dimensions(1000, 500)->responsive(true)->title('Usuarios creados en el último mes')->LastByMonth(1, true);

        return view('admin.reportes.layout', ['Chart' => $historicoCreacionUsuarios]);
    }

    public function pqr_respondidasATiempo()
    {
        $pqrs = gd_pqr::whereHas('hasClase', function($query){
            $query->where('required_answer', 'SI');
        })->has('hasRespuestas')->where('tipo_pqr','!=','CoSa')->get();
        $pqrs = $pqrs->filter(function($data){
            return $data->hasRespuestas[0]->created_at <= $data->limite_respuesta;
        });

        $chart = Charts::database($pqrs, 'bar', 'highcharts')->elementLabel("Cantidad")->title('Respondidas a tiempo')->dimensions(1000, 500)->responsive(true)->groupByMonth(Setting::get('vigencia'), true);

        return view('admin.reportes.layout', ['Chart' => $chart]);
    }

    public function pqr_respondidasFueraTiempo()
    {
        $pqrs = \DB::table('gd_pqr')->join('gd_pqr_respuesta', 'gd_pqr.id', '=', 'gd_pqr_respuesta.gd_pqr_respondido_id')->select('gd_pqr.*')->where('gd_pqr.limite_respuesta', '<', 'pqr_respuesta.created_at')->get();

        $chart = Charts::database($pqrs, 'bar', 'highcharts')->elementLabel("Cantidad")->title('Respondidas fuera de tiempo')->dimensions(1000, 500)->responsive(true)->groupByMonth(Setting::get('vigencia'), true);

        return view('admin.reportes.layout', ['Chart' => $chart]);
    }

    public function pqr_sinResponder()
    {
        $pqrs = gd_pqr::doesntHave('hasRespuestas')->where('tipo_pqr','!=','CoSa')->whereHas('hasClase', function($query){
            $query->where('required_answer','SI');
        })->get();

        return view('admin.reportes.counter', ['number' => $pqrs->count(), 'title' => 'Procesos sin responder']);
    }

    public function pqr_vencidas()
    {
        $pqrs = gd_pqr::doesntHave('hasRespuestas')->where('tipo_pqr','!=','CoSa')->whereHas('hasClase', function($query){
            $query->where('required_answer','SI');
        })->where('limite_respuesta', '<', date('Y-m-d'))->get();

        return view('admin.reportes.counter', [
            'number' => $pqrs->count(),
            'title' => 'Procesos vencidos sin responder',
        ]);
    }

    public function pqr_porVencer()
    {
        $pqrs = gd_pqr::doesntHave('hasRespuestas')->where('tipo_pqr','!=','CoSa')->whereHas('hasClase', function($query){
            $query->where('required_answer','SI');
        })->whereBetween('limite_respuesta', [
            Carbon::now(),
            Carbon::now()->addDay(2),
        ])->get();

        return view('admin.reportes.counter', ['number' => $pqrs->count(), 'title' => 'Procesos a punto de vencer']);
    }

    public function pqr_pqrPorClases()
    {
        $clases = gd_pqr_clase::orderBy('name')->get();
        if ($clases != null) {
            $values = [];
            $mValues = [];
            $i = 0;
            $totales = [
                0 => [
                    'CoEx' => 0,
                    'CoIn' => 0,
                    'CoSa' => 0
                ],
                1 => [
                    'CoEx' => 0,
                    'CoIn' => 0,
                    'CoSa' => 0
                ],
                2 => [
                    'CoEx' => 0,
                    'CoIn' => 0,
                    'CoSa' => 0
                ],
                3 => [
                    'CoEx' => 0,
                    'CoIn' => 0,
                    'CoSa' => 0
                ],
                4 => [
                    'CoEx' => 0,
                    'CoIn' => 0,
                    'CoSa' => 0
                ],
                5 => [
                    'CoEx' => 0,
                    'CoIn' => 0,
                    'CoSa' => 0
                ],
                6 => [
                    'CoEx' => 0,
                    'CoIn' => 0,
                    'CoSa' => 0
                ],
                7 => [
                    'CoEx' => 0,
                    'CoIn' => 0,
                    'CoSa' => 0
                ],
                8 => [
                    'CoEx' => 0,
                    'CoIn' => 0,
                    'CoSa' => 0
                ],
                9 => [
                    'CoEx' => 0,
                    'CoIn' => 0,
                    'CoSa' => 0
                ],
                10 => [
                    'CoEx' => 0,
                    'CoIn' => 0,
                    'CoSa' => 0
                ],
                11 => [
                    'CoEx' => 0,
                    'CoIn' => 0,
                    'CoSa' => 0
                ],
            ];
            foreach ($clases as $clase) {
                for ($p = 1; $p <= 12; $p++) {
                    $pqrs = \DB::table('gd_pqr')->where('gd_pqr_clase_id', $clase->id)->whereYear('created_at',Setting::get('vigencia'))->whereMonth('created_at', $p)->get();
                    $mValues['CoEx'][$p - 1] = count($pqrs->filter(function($data){
                        return $data->tipo_pqr == 'CoEx';
                    }));
                    $mValues['CoIn'][$p - 1] = count($pqrs->filter(function($data){
                        return $data->tipo_pqr == 'CoIn';
                    }));
                    $mValues['CoSa'][$p - 1] = count($pqrs->filter(function($data){
                        return $data->tipo_pqr == 'CoSa';
                    }));
                    $totales[$p-1]['CoEx'] = $mValues['CoEx'][$p - 1] + $totales[$p-1]['CoEx'];
                    $totales[$p-1]['CoIn'] = $mValues['CoIn'][$p - 1] + $totales[$p-1]['CoIn'];
                    $totales[$p-1]['CoSa'] = $mValues['CoSa'][$p - 1] + $totales[$p-1]['CoSa'];
                }
                $values[$i] = [$clase->name, $mValues];
                $mValues = null;
                $i = $i + 1;
            }
            return view('admin.reportes.pqr.generalRadicadosMeses', ['clases'=>$values,'totales'=>$totales, 'title'=>'PROCESOS PQRS POR TIPO Y CLASES'])->render();
        } else {
            return null;
        }
    }
    
    public function SolicitudesTramitesPorAñosYMeses()
    {
        $currentYear = date(Setting::get('vigencia'));
        $data = [];
        $month = [];
        for($i=$currentYear-3;$i<=$currentYear;$i++){
            for($e=1;$e<=12;$e++){
                array_push($month, \DB::table('tramite_solicitud')->whereYear('created_at', $i)->whereMonth('created_at', $e)->count());
            }
            array_push($data, ['year'=>$i,'data'=>$month]);
            $month = [];
        }

        $chart = new ChartSolicitudesTramitesPorAñosYMeses;
        $chart->title("Solicitudes por años y meses");
        $chart->labels(['Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre']);
        $chart->dataset($data[0]['year'], 'column', $data[0]['data']);
        $chart->dataset($data[1]['year'], 'column', $data[1]['data']);
        $chart->dataset($data[2]['year'], 'column', $data[2]['data']);
        $chart->dataset($data[3]['year'], 'column', $data[3]['data']);
        
        return view('admin.reportes.layout', ['chart' => $chart]);
    }
    
    public function SolicitudesTramitesPorDias($month = null)
    {
        $solicitudes = tramite_solicitud::whereMonth('created_at', 4)->orderBy('created_at')->get()->groupBy(function($item) {
            return $item->created_at->format('D d');
        })->map(function ($item) {
            return count($item);
        });
        $chart = new ChartSolicitudesTramitesPorDias;        
        $chart->title('Solicitudes por Días');
        $chart->labels($solicitudes->keys());
        $chart->dataset('Solicitudes', 'line', $solicitudes->values());
        $chart->options(['plotOptions'=>[
                    'line'=>[
                        'dataLabels'=>[
                            'enabled' => true
                        ]                                            
                    ]             
                ],
                'yAxis' => [
                    'title' => [
                        'text' => 'Cantidad'
                    ]
                ],
                'xAxis' => [
                    'labels' => [
                        'autoRotation' => [-10, -20, -30, -40, -50, -60, -70, -80, -90]
                    ]
                ]        
            ]);
        
        return view('admin.reportes.layout', ['chart' => $chart]);
    }
    
    public function SolicitudesTramitesPorTramites($year = null)
    {
        $tramites = tramite::all();
        $currentYear = date(\Setting::get('vigencia'));
        $data = [];
        $month = [];     
        foreach($tramites as $tramite){
            for($e=1;$e<=12;$e++){
                array_push($month, \DB::table('tramite_solicitud')->join('tramite_solicitud_has_tramite', 'tramite_solicitud.id', '=', 'tramite_solicitud_has_tramite.tramite_solicitud_id')->join('tramite', 'tramite_solicitud_has_tramite.tramite_id', '=', 'tramite.id')->where('tramite.id', $tramite->id)->whereYear('tramite_solicitud.created_at', \Setting::get('vigencia'))->whereMonth('tramite_solicitud.created_at', $e)->count());   
            }
            array_push($data, ['data'=>$month, 'tramite'=>$tramite->name]);
            $month = [];  
        }           
        //dd($data);
        $chart = new ChartSolicitudesTramitesPorTramites;        
        $chart->title('Solicitudes por trámites');
        $chart->labels(['Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre']);
        $chart->dataset($data[0]['tramite'], 'column', $data[0]['data']);
        $chart->dataset($data[1]['tramite'], 'column', $data[1]['data']);
        $chart->dataset($data[2]['tramite'], 'column', $data[2]['data']);
        $chart->dataset($data[3]['tramite'], 'column', $data[3]['data']);
        $chart->dataset($data[4]['tramite'], 'column', $data[4]['data']);
        $chart->dataset($data[5]['tramite'], 'column', $data[5]['data']);
        $chart->dataset($data[6]['tramite'], 'column', $data[6]['data']);
        $chart->dataset($data[7]['tramite'], 'column', $data[7]['data']);
        $chart->dataset($data[8]['tramite'], 'column', $data[8]['data']);
        $chart->dataset($data[9]['tramite'], 'column', $data[9]['data']);
        $chart->dataset($data[10]['tramite'], 'column', $data[10]['data']);
        $chart->dataset($data[11]['tramite'], 'column', $data[11]['data']);
        /*$chart->options(['plotOptions'=>[
                    'series'=>[
                        'pointWidth' => 8, 
                        'minimalist' => true,
                        'groupPadding' => 0
                    ],
                    'column'=>[
                        'dataLabels'=>[
                            'enabled' => true
                        ]
                    ]
                ]
            ]);*/

        return view('admin.reportes.layout', ['chart' => $chart]);
    }
    
    public function SolicitudesTramitesPorEstadosAsignados()
    {
        $estados = tramite_servicio_estado::all();
        if ($estados != null) {
            $values = [];

            foreach ($estados as $estado) {
                array_push($values, $estado->getTramitesServicios()->count());
            }

            $estadosAsignados = Charts::database(tramite_servicio_estado::all(), 'pie', 'highcharts')->elementLabel("Cantidad")->labels($estados->pluck('name'))->dimensions(1000, 500)->responsive(true)->values($values)->title('Estados Asignados');

            return view('admin.reportes.layout', ['Chart' => $estadosAsignados]);
        } else {
            return null;
        }
    }

    public function SolicitudesTramitesTurnosGenerados()
    {
        $turnos = tramite_solicitud_turno::all();
        return view('admin.reportes.counter', [
            'number' => $turnos->count(),
            'title' => 'Turnos generados',
        ]);
    }

    public function SolicitudesTramitesTurnosPreferentes()
    {
        $turnos = tramite_solicitud_turno::where('preferente', 1)->get();
        return view('admin.reportes.counter', [
            'number' => $turnos->count(),
            'title' => 'Turnos preferentes',
        ]);
    }

    public function SolicitudesTramitesTurnosAnulados()
    {
        $turnos = tramite_solicitud_turno::where('fecha_anulacion', '!=', null)->get();
        return view('admin.reportes.counter', [
            'number' => $turnos->count(),
            'title' => 'Turnos anulados',
        ]);
    }

    public function SolicitudesTramitesTurnosVencidos()
    {
        $turnos = tramite_solicitud_turno::where('fecha_vencimiento', '!=', null)->get();
        return view('admin.reportes.counter', [
            'number' => $turnos->count(),
            'title' => 'Turnos vencidos',
        ]);
    }

    public function SolicitudesTramitesTurnosReLlamados()
    {
        $turnos = tramite_solicitud_turno::where('funcionario_rellamado_id', '!=', null)->get();
        return view('admin.reportes.counter', [
            'number' => $turnos->count(),
            'title' => 'Turnos re-llamados',
        ]);
    }

    public function SolicitudesTramitesTurnosPorOrigen()
    {
        $origenes = tramite_solicitud_origen::all();
        if ($origenes != null) {
            $values = [];

            foreach ($origenes as $origen) {
                array_push($values, $origen->getTramitesSolicitudes()->count());
            }

            $turnosPorOrigen = Charts::database(tramite_solicitud_origen::all(), 'pie', 'highcharts')->elementLabel("Cantidad")->labels($origenes->pluck('name'))->dimensions(1000, 500)->responsive(true)->values($values)->title('Turnos por Origen');

            return view('admin.reportes.layout', ['Chart' => $turnosPorOrigen]);
        } else {
            return null;
        }
    }

    public function SustratosConsumidosPorAños()
    {
        $sustratos = sustrato::whereHas('hasTramiteFinalizacion', function ($q){
            $q->whereYear('created_at', Setting::get('vigencia'));
        })->orWhereHas('hasLicencia', function ($q){
            $q->whereYear('created_at', Setting::get('vigencia'));
        })->get();
        $sustratos = Charts::database($sustratos, 'bar', 'highcharts')->elementLabel("Total")->title('Sustratos Consumidos por Años')->dimensions(500, 500)->responsive(true)->groupByYear(4);

        return view('admin.reportes.layout', ['Chart' => $sustratos]);
    }

    public function SustratosConsumidosPorMeses()
    {
        $sustratos = sustrato::whereHas('hasTramiteFinalizacion', function ($q){
            $q->whereYear('created_at', Setting::get('vigencia'));
        })->orWhereHas('hasLicencia', function ($q){
            $q->whereYear('created_at', Setting::get('vigencia'));
        })->get();
        $sustratos = Charts::database($sustratos, 'bar', 'highcharts')->elementLabel("Total")->title('Solicitudes por Meses')->dimensions(500, 500)->responsive(true)->groupByMonth(Setting::get('vigencia'), true);

        return view('admin.reportes.layout', ['Chart' => $sustratos]);
    }

    public function SustratosConsumidosPorDias()
    {
        $sustratos = sustrato::whereHas('hasTramiteFinalizacion', function ($q){
            $q->whereYear('created_at', Setting::get('vigencia'));
        })->orWhereHas('haslicencia', function ($q){
            $q->whereYear('created_at', Setting::get('vigencia'));
        })->get();
        $sustratos = Charts::database($sustratos, 'bar', 'highcharts')->elementLabel("Total")->title('Solicitudes por Meses')->dimensions(500, 500)->responsive(true)->groupByDay();

        return view('admin.reportes.layout', ['Chart' => $sustratos]);
    }

    public function SustratosPorTipo()
    {
        $tipos = tipo_sustrato::all();
        if ($tipos != null) {
            $values = [];

            foreach ($tipos as $tipo) {
                array_push($values, $tipo->hasSustratos()->count());
            }

            $sustratosPorTipo = Charts::database(tipo_sustrato::all(), 'pie', 'highcharts')->elementLabel("Cantidad")->labels($tipos->pluck('name'))->dimensions(1000, 500)->responsive(true)->values($values)->title('Sustratos por Tipo');

            return view('admin.reportes.layout', ['Chart' => $sustratosPorTipo]);
        } else {
            return null;
        }
    }

    public function PlacasConsumidasPorAños()
    {
        $placas = placa::whereHas('hasConsumo', function ($q){
            $q->whereYear('created_at', Setting::get('vigencia'));
        })->get();
        $placas = Charts::database($placas, 'bar', 'highcharts')->elementLabel("Total")->title('Placas Consumidos por Años')->dimensions(500, 500)->responsive(true)->groupByYear(4);

        return view('admin.reportes.layout', ['Chart' => $placas]);
    }

    public function PlacasConsumidasPorMeses()
    {
        $placas = placa::whereHas('hasConsumo', function ($q){
            $q->whereYear('created_at', Setting::get('vigencia'));
        })->get();
        $placas = Charts::database($placas, 'bar', 'highcharts')->elementLabel("Total")->title('Placas Consumidas por Meses')->dimensions(500, 500)->responsive(true)->groupByMonth(Setting::get('vigencia'), true);

        return view('admin.reportes.layout', ['Chart' => $placas]);
    }

    public function PlacasConsumidasPorDias()
    {
        $placas = placa::whereHas('hasConsumo', function ($q){
            $q->whereYear('created_at', Setting::get('vigencia'));
        })->get();
        $placas = Charts::database($placas, 'bar', 'highcharts')->elementLabel("Total")->title('Placas Consumidas por Meses')->dimensions(500, 500)->responsive(true)->groupByDay();

        return view('admin.reportes.layout', ['Chart' => $placas]);
    }

    public function PlacasPorServicioVehiculo()
    {
        $servicios = vehiculo_servicio::all();
        if ($servicios != null) {
            $values = [];

            foreach ($servicios as $servicio) {
                array_push($values, $servicio->hasPlacas()->count());
            }

            $placasPorServicio = Charts::database(vehiculo_servicio::all(), 'pie', 'highcharts')->elementLabel("Cantidad")->labels($servicios->pluck('name'))->dimensions(1000, 500)->responsive(true)->values($values)->title('Placas por Servicio de Vehículo');

            return view('admin.reportes.layout', ['Chart' => $placasPorServicio]);
        } else {
            return null;
        }
    }

    public function PreAsignacionesPorAñosMeses()
    {
        $currentYear = date(Setting::get('vigencia'));
        $data = [];
        $month = [];
        for($i=$currentYear-3;$i<=$currentYear;$i++){
            for($e=1;$e<=12;$e++){
                array_push($month, \DB::table('solicitud_preasignacion')->whereYear('created_at', $i)->whereMonth('created_at', $e)->count());
            }
            array_push($data, ['year'=>$i,'data'=>$month]);
            $month = [];
        }

        $chart = Charts::multi('bar', 'highcharts')
            ->title("PreAsignaciones por años y meses")
            ->elementLabel("Total")
            ->responsive(true)
            ->template("material")
            ->legend(true)
            ->dimensions(1000, 500)
            ->labels(['Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'])
            ->dataset($data[0]['year'], $data[0]['data'])
            ->dataset($data[1]['year'], $data[1]['data'])
            ->dataset($data[2]['year'], $data[2]['data'])
            ->dataset($data[3]['year'], $data[3]['data']);

        return view('admin.reportes.layout', ['Chart' => $chart]);
    }

    public function PreAsignacionesPorClaseVehiculo()
    {
        $clases = vehiculo_clase::all();
        if ($clases != null) {
            $values = [];

            foreach ($clases as $clase) {
                array_push($values, $clase->hasPreAsignaciones()->count());
            }

            $preAsignacionesPorClase = Charts::database(vehiculo_clase::all(), 'pie', 'highcharts')->elementLabel("Cantidad")->labels($clases->pluck('name'))->dimensions(1000, 500)->responsive(true)->values($values)->title('PreAsignaciones por Clase de Vehículo');

            return view('admin.reportes.layout', ['Chart' => $preAsignacionesPorClase]);
        } else {
            return null;
        }
    }

    public function PreAsignacionesPorServicioVehiculo()
    {
        $servicios = vehiculo_servicio::all();
        if ($servicios != null) {
            $values = [];

            foreach ($servicios as $servicio) {
                array_push($values, $servicio->hasPreAsignaciones()->count());
            }

            $preAsignacionesPorServicio = Charts::database(vehiculo_servicio::all(), 'pie', 'highcharts')->elementLabel("Cantidad")->labels($servicios->pluck('name'))->dimensions(1000, 500)->responsive(true)->values($values)->title('PreAsignaciones por Servicio de Vehículo');

            return view('admin.reportes.layout', ['Chart' => $preAsignacionesPorServicio]);
        } else {
            return null;
        }
    }

    public function ventanillaFuncionarioSolicitudesAtentidadasPorAñosYMeses()
    {
        $currentYear = date(Setting::get('vigencia'));
        $data = [];
        $month = [];
        for($i=$currentYear-3;$i<=$currentYear;$i++){
            for($e=1;$e<=12;$e++){
                array_push($month, \DB::table('tramite_solicitud_asignacion')->whereYear('created_at', $i)->whereMonth('created_at', $e)->where('funcionario_id',auth()->user()->id)->count());
            }
            array_push($data, ['year'=>$i,'data'=>$month]);
            $month = [];
        }

        $chart = Charts::multi('bar', 'highcharts')
            ->title("Funcionario: Solicitudes atendidas por años y meses")
            ->elementLabel("Total")
            ->responsive(true)
            ->template("material")
            ->legend(true)
            ->dimensions(1000, 500)
            ->labels(['Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'])
            ->dataset($data[0]['year'], $data[0]['data'])
            ->dataset($data[1]['year'], $data[1]['data'])
            ->dataset($data[2]['year'], $data[2]['data'])
            ->dataset($data[3]['year'], $data[3]['data']);

        return view('admin.reportes.layout', ['Chart' => $chart]);
    }

    public function ventanillaSolicitudesAtendidasPorAñosYMeses($ventanillaId)
    {
        $currentYear = date(Setting::get('vigencia'));
        $data = [];
        $month = [];
        for($i=$currentYear-3;$i<=$currentYear;$i++){
            for($e=1;$e<=12;$e++){
                array_push($month, \DB::table('tramite_solicitud_asignacion')->whereYear('created_at', $i)->whereMonth('created_at', $e)->where('ventanilla_id', $ventanillaId)->count());
            }
            array_push($data, ['year'=>$i,'data'=>$month]);
            $month = [];
        }

        $chart = Charts::multi('bar', 'highcharts')
            ->title("Ventanilla: Solicitudes atendidas por años y meses")
            ->elementLabel("Total")
            ->responsive(true)
            ->template("material")
            ->legend(true)
            ->dimensions(1000, 500)
            ->labels(['Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'])
            ->dataset($data[0]['year'], $data[0]['data'])
            ->dataset($data[1]['year'], $data[1]['data'])
            ->dataset($data[2]['year'], $data[2]['data'])
            ->dataset($data[3]['year'], $data[3]['data']);

        return view('admin.reportes.layout', ['Chart' => $chart]);
    }

    public function ventanillaYFuncionarioSolicitudesAtendidasPorAñosYMeses($ventanillaId)
    {
        $currentYear = date(Setting::get('vigencia'));
        $data = [];
        $month = [];
        for($i=$currentYear-3;$i<=$currentYear;$i++){
            for($e=1;$e<=12;$e++){
                array_push($month, \DB::table('tramite_solicitud_asignacion')->whereYear('created_at', $i)->whereMonth('created_at', $e)->where('funcionario_id',auth()->user()->id)->where('ventanilla_id', $ventanillaId)->count());
            }
            array_push($data, ['year'=>$i,'data'=>$month]);
            $month = [];
        }

        $chart = Charts::multi('bar', 'highcharts')
            ->title("Ventanilla y Funcionario: Solicitudes atendidas por años y meses")
            ->elementLabel("Total")
            ->responsive(true)
            ->template("material")
            ->legend(true)
            ->dimensions(1000, 500)
            ->labels(['Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'])
            ->dataset($data[0]['year'], $data[0]['data'])
            ->dataset($data[1]['year'], $data[1]['data'])
            ->dataset($data[2]['year'], $data[2]['data'])
            ->dataset($data[3]['year'], $data[3]['data']);

        return view('admin.reportes.layout', ['Chart' => $chart]);
    }

    public function SolicitudesTramitesPorEstado()
    {
        $estados = tramite_servicio_estado::all();
        if ($estados != null) {
            $values = [];

            foreach ($estados as $estado) {
                array_push($values, $estado->getTramitesServicios()->count());
            }

            $solicitudesPorEstado = Charts::database(tramite_servicio_estado::all(), 'pie', 'highcharts')->elementLabel("Cantidad")->labels($estados->pluck('name'))->dimensions(1000, 500)->responsive(true)->values($values)->title('Solicitudes por Estado');

            return view('admin.reportes.layout', ['Chart' => $solicitudesPorEstado]);
        } else {
            return null;
        }
    }

    public function pqr_GeneralPorAñosYMeses()
    {
        $currentYear = date(Setting::get('vigencia'));
        $data = [];
        $month = [];
        for($i=$currentYear-3;$i<=$currentYear;$i++){
            for($e=1;$e<=12;$e++){
                array_push($month, \DB::table('gd_pqr')->whereYear('created_at', $i)->whereMonth('created_at', $e)->count());
            }
            array_push($data, ['year'=>$i,'data'=>$month]);
            $month = [];
        }

        $chart = Charts::multi('bar', 'highcharts')
            ->title("Correspondencia General: Radicadas por años y meses")
            ->elementLabel("Total")
            ->responsive(true)
            ->template("material")
            ->legend(true)
            ->dimensions(1000, 500)
            ->labels(['Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'])
            ->dataset($data[0]['year'], $data[0]['data'])
            ->dataset($data[1]['year'], $data[1]['data'])
            ->dataset($data[2]['year'], $data[2]['data'])
            ->dataset($data[3]['year'], $data[3]['data']);

        return view('admin.reportes.layout', ['Chart' => $chart]);
    }

    public function pqr_CoExPorAñosYMeses()
    {
        $currentYear = date(Setting::get('vigencia'));
        $data = [];
        $month = [];
        for($i=$currentYear-3;$i<=$currentYear;$i++){
            for($e=1;$e<=12;$e++){
                array_push($month, \DB::table('gd_pqr')->whereYear('created_at', $i)->whereMonth('created_at', $e)->where('tipo_pqr','CoEx')->count());
            }
            array_push($data, ['year'=>$i,'data'=>$month]);
            $month = [];
        }

        $chart = Charts::multi('bar', 'highcharts')
            ->title("Correspondencia Externa: Radicadas por años y meses")
            ->elementLabel("Total")
            ->responsive(true)
            ->template("material")
            ->legend(true)
            ->dimensions(1000, 500)
            ->labels(['Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'])
            ->dataset($data[0]['year'], $data[0]['data'])
            ->dataset($data[1]['year'], $data[1]['data'])
            ->dataset($data[2]['year'], $data[2]['data'])
            ->dataset($data[3]['year'], $data[3]['data']);

        return view('admin.reportes.layout', ['Chart' => $chart]);
    }

    public function pqr_CoInPorAñosYMeses()
    {
        $currentYear = date(Setting::get('vigencia'));
        $data = [];
        $month = [];
        for($i=$currentYear-3;$i<=$currentYear;$i++){
            for($e=1;$e<=12;$e++){
                array_push($month, \DB::table('gd_pqr')->whereYear('created_at', $i)->whereMonth('created_at', $e)->where('tipo_pqr','CoIn')->count());
            }
            array_push($data, ['year'=>$i,'data'=>$month]);
            $month = [];
        }

        $chart = Charts::multi('bar', 'highcharts')
            ->title("Correspondencia Interna: Radicadas por años y meses")
            ->elementLabel("Total")
            ->responsive(true)
            ->template("material")
            ->legend(true)
            ->dimensions(1000, 500)
            ->labels(['Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'])
            ->dataset($data[0]['year'], $data[0]['data'])
            ->dataset($data[1]['year'], $data[1]['data'])
            ->dataset($data[2]['year'], $data[2]['data'])
            ->dataset($data[3]['year'], $data[3]['data']);

        return view('admin.reportes.layout', ['Chart' => $chart]);
    }

    public function pqr_CoSaPorAñosYMeses()
    {
        $currentYear = date(Setting::get('vigencia'));
        $data = [];
        $month = [];
        for($i=$currentYear-3;$i<=$currentYear;$i++){
            for($e=1;$e<=12;$e++){
                array_push($month, \DB::table('gd_pqr')->whereYear('created_at', $i)->whereMonth('created_at', $e)->where('tipo_pqr','CoSa')->count());
            }
            array_push($data, ['year'=>$i,'data'=>$month]);
            $month = [];
        }

        $chart = Charts::multi('bar', 'highcharts')
            ->title("Correspondencia Saliente: Radicadas por años y meses")
            ->elementLabel("Total")
            ->responsive(true)
            ->template("material")
            ->legend(true)
            ->dimensions(1000, 500)
            ->labels(['Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'])
            ->dataset($data[0]['year'], $data[0]['data'])
            ->dataset($data[1]['year'], $data[1]['data'])
            ->dataset($data[2]['year'], $data[2]['data'])
            ->dataset($data[3]['year'], $data[3]['data']);

        return view('admin.reportes.layout', ['Chart' => $chart]);
    }

    public function pqr_PorMedioTraslado()
    {
        $medios = gd_medio_traslado::all();
        if ($medios != null) {
            $values = [];

            foreach ($medios as $medio) {
                array_push($values, $medio->hasPQRS()->count());
            }

            $pqrPorMedio = Charts::database(gd_medio_traslado::all(), 'pie', 'highcharts')->elementLabel("Cantidad")->labels($medios->pluck('name'))->dimensions(1000, 500)->responsive(true)->values($values)->title('PQR por Medio de Traslado');

            return view('admin.reportes.layout', ['Chart' => $pqrPorMedio]);
        } else {
            return null;
        }
    }

    public function pqr_GeneralAnuladasPorAñosYMeses()
    {
        $currentYear = date(Setting::get('vigencia'));
        $data = [];
        $month = [];
        for($i=$currentYear-3;$i<=$currentYear;$i++){
            for($e=1;$e<=12;$e++){
                array_push($month, \DB::table('gd_pqr')->whereYear('created_at', $i)->whereMonth('created_at', $e)->whereExists(function ($query) {
                    $query->select(\DB::raw(1))
                          ->from('gd_pqr_anulacion')
                          ->whereRaw('gd_pqr_anulacion.gd_pqr_id = gd_pqr.id');
                })->count());
            }
            array_push($data, ['year'=>$i,'data'=>$month]);
            $month = [];
        }

        $chart = Charts::multi('bar', 'highcharts')
            ->title("Correspondencia General: Anuladas por años y meses")
            ->elementLabel("Total")
            ->responsive(true)
            ->template("material")
            ->legend(true)
            ->dimensions(1000, 500)
            ->labels(['Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'])
            ->dataset($data[0]['year'], $data[0]['data'])
            ->dataset($data[1]['year'], $data[1]['data'])
            ->dataset($data[2]['year'], $data[2]['data'])
            ->dataset($data[3]['year'], $data[3]['data']);

        return view('admin.reportes.layout', ['Chart' => $chart]);
    }

    public function pqr_CoExAnuladasPorAñosYMeses()
    {
        $currentYear = date(Setting::get('vigencia'));
        $data = [];
        $month = [];
        for($i=$currentYear-3;$i<=$currentYear;$i++){
            for($e=1;$e<=12;$e++){
                array_push($month, \DB::table('gd_pqr')->whereYear('created_at', $i)->whereMonth('created_at', $e)->where('tipo_pqr','CoEx')->whereExists(function ($query) {
                    $query->select(\DB::raw(1))
                          ->from('gd_pqr_anulacion')
                          ->whereRaw('gd_pqr_anulacion.gd_pqr_id = gd_pqr.id');
                })->count());
            }
            array_push($data, ['year'=>$i,'data'=>$month]);
            $month = [];
        }

        $chart = Charts::multi('bar', 'highcharts')
            ->title("Correspondencia Externa: Anuladas por años y meses")
            ->elementLabel("Total")
            ->responsive(true)
            ->template("material")
            ->legend(true)
            ->dimensions(1000, 500)
            ->labels(['Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'])
            ->dataset($data[0]['year'], $data[0]['data'])
            ->dataset($data[1]['year'], $data[1]['data'])
            ->dataset($data[2]['year'], $data[2]['data'])
            ->dataset($data[3]['year'], $data[3]['data']);

        return view('admin.reportes.layout', ['Chart' => $chart]);
    }

    public function pqr_CoInAnuladasPorAñosYMeses()
    {
        $currentYear = date(Setting::get('vigencia'));
        $data = [];
        $month = [];
        for($i=$currentYear-3;$i<=$currentYear;$i++){
            for($e=1;$e<=12;$e++){
                array_push($month, \DB::table('gd_pqr')->whereYear('created_at', $i)->whereMonth('created_at', $e)->where('tipo_pqr','CoIn')->whereExists(function ($query) {
                    $query->select(\DB::raw(1))
                          ->from('gd_pqr_anulacion')
                          ->whereRaw('gd_pqr_anulacion.gd_pqr_id = gd_pqr.id');
                })->count());
            }
            array_push($data, ['year'=>$i,'data'=>$month]);
            $month = [];
        }

        $chart = Charts::multi('bar', 'highcharts')
            ->title("Correspondencia Interna: Anuladas por años y meses")
            ->elementLabel("Total")
            ->responsive(true)
            ->template("material")
            ->legend(true)
            ->dimensions(1000, 500)
            ->labels(['Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'])
            ->dataset($data[0]['year'], $data[0]['data'])
            ->dataset($data[1]['year'], $data[1]['data'])
            ->dataset($data[2]['year'], $data[2]['data'])
            ->dataset($data[3]['year'], $data[3]['data']);

        return view('admin.reportes.layout', ['Chart' => $chart]);
    }

    public function pqr_CoSaAnuladasPorAñosYMeses()
    {
        $currentYear = date(Setting::get('vigencia'));
        $data = [];
        $month = [];
        for($i=$currentYear-3;$i<=$currentYear;$i++){
            for($e=1;$e<=12;$e++){
                array_push($month, \DB::table('gd_pqr')->whereYear('created_at', $i)->whereMonth('created_at', $e)->where('tipo_pqr','CoSa')->whereExists(function ($query) {
                    $query->select(\DB::raw(1))
                          ->from('gd_pqr_anulacion')
                          ->whereRaw('gd_pqr_anulacion.gd_pqr_id = gd_pqr.id');
                })->count());
            }
            array_push($data, ['year'=>$i,'data'=>$month]);
            $month = [];
        }

        $chart = Charts::multi('bar', 'highcharts')
            ->title("Correspondencia Saliente: Anuladas por años y meses")
            ->elementLabel("Total")
            ->responsive(true)
            ->template("material")
            ->legend(true)
            ->dimensions(1000, 500)
            ->labels(['Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'])
            ->dataset($data[0]['year'], $data[0]['data'])
            ->dataset($data[1]['year'], $data[1]['data'])
            ->dataset($data[2]['year'], $data[2]['data'])
            ->dataset($data[3]['year'], $data[3]['data']);

        return view('admin.reportes.layout', ['Chart' => $chart]);
    }

    public function dependencia_FuncionariosPorDependencia()
    {
        $dependencias = dependencia::all();
        if ($dependencias != null) {
            $values = [];

            foreach ($dependencias as $dependencia) {
                array_push($values, $dependencia->hasFuncionarios()->count());
            }

            $funcionariosPorDependencia = Charts::database(dependencia::all(), 'pie', 'highcharts')->elementLabel("Cantidad")->labels($dependencias->pluck('name'))->dimensions(1000, 500)->responsive(true)->values($values)->title('Funcionarios por Dependencia');

            return view('admin.reportes.layout', ['Chart' => $funcionariosPorDependencia]);
        } else {
            return null;
        }
    }

    public function empresaTransporte_VehiculosPorEmpresa()
    {
        $empresasTransporte = empresa_transporte::all();
        if ($empresasTransporte != null) {
            $values = [];

            foreach ($empresasTransporte as $empresaTransporte) {
                array_push($values, $empresaTransporte->hasVehiculosAfiliados()->count());
            }

            $vehiculosPorEmpresaTransporte = Charts::database(empresa_transporte::all(), 'pie', 'highcharts')->elementLabel("Cantidad")->labels($empresasTransporte->pluck('name'))->dimensions(1000, 500)->responsive(true)->values($values)->title('Vehículos por Empresa Transporte');

            return view('admin.reportes.layout', ['Chart' => $vehiculosPorEmpresaTransporte]);
        } else {
            return null;
        }
    }

    public function empresaTransporte_TarjetasOperacionActivasPorEmpresa()
    {
        $empresasTransporte = empresa_transporte::all();
        if ($empresasTransporte != null) {
            $values = [];

            foreach ($empresasTransporte as $empresaTransporte) {
                array_push($values, $empresaTransporte->hasManyTOS()->whereDate('fecha_vencimiento','<=',date('Y-m-d'))->count());
            }

            $vehiculosPorEmpresaTransporte = Charts::database(empresa_transporte::all(), 'pie', 'highcharts')->elementLabel("Cantidad")->labels($empresasTransporte->pluck('name'))->dimensions(1000, 500)->responsive(true)->values($values)->title('Tarjetas de Operación Activas por Empresa Transporte');

            return view('admin.reportes.layout', ['Chart' => $vehiculosPorEmpresaTransporte]);
        } else {
            return null;
        }
    }

    public function roles_FuncionariosPorRol()
    {
        $roles = Role::all();
        if ($roles != null) {
            $values = [];

            foreach ($roles as $rol) {
                array_push($values, $rol->tieneUsuarios()->count());
            }

            $funcionariosPorRol = Charts::database(Role::all(), 'pie', 'highcharts')->elementLabel("Cantidad")->labels($roles->pluck('name'))->dimensions(1000, 500)->responsive(true)->values($values)->title('Funcionarios por Rol');

            return view('admin.reportes.layout', ['Chart' => $funcionariosPorRol]);
        } else {
            return null;
        }
    }

    public function usuarios_FuncionariosTwoFactor()
    {
        $usuarios = User::where('google2fa_secret','!=', ' ')->get();

        return view('admin.reportes.counter', ['number' => $usuarios->count(), 'title' => 'Funcionarios con TwoFactor Authentication']);
    }

    public function usuarios_FuncionariosBloqueados()
    {
        $usuarios = User::where('lock_session','yes')->get();

        return view('admin.reportes.counter', ['number' => $usuarios->count(), 'title' => 'Funcionarios Bloqueados']);
    }

    public function vehiculos_VehiculosPorMarca()
    {
        $marcas = vehiculo_marca::all();
        if ($marcas != null) {
            $values = [];

            foreach ($marcas as $marca) {
                array_push($values, $marca->hasVehiculos()->count());
            }

            $vehiculosPorMarca = Charts::database(vehiculo_marca::all(), 'pie', 'highcharts')->elementLabel("Cantidad")->labels($marcas->pluck('name'))->dimensions(1000, 500)->responsive(true)->values($values)->title('Vehículos por Marca');

            return view('admin.reportes.layout', ['Chart' => $vehiculosPorMarca]);
        } else {
            return null;
        }
    }

    public function vehiculos_VehiculosPorClase()
    {
        $clases = vehiculo_clase::all();
        if ($clases != null) {
            $values = [];

            foreach ($clases as $clase) {
                array_push($values, $clase->hasVehiculos()->count());
            }

            $vehiculosPorClase = Charts::database(vehiculo_clase::all(), 'pie', 'highcharts')->elementLabel("Cantidad")->labels($clases->pluck('name'))->dimensions(1000, 500)->responsive(true)->values($values)->title('Vehículos por Clase');

            return view('admin.reportes.layout', ['Chart' => $vehiculosPorClase]);
        } else {
            return null;
        }
    }

    public function vehiculos_VehiculosPorCombustible()
    {
        $combustibles = vehiculo_combustible::all();
        if ($combustibles != null) {
            $values = [];

            foreach ($combustibles as $combustible) {
                array_push($values, $combustible->hasVehiculos()->count());
            }

            $vehiculosPorCombustible = Charts::database(vehiculo_combustible::all(), 'pie', 'highcharts')->elementLabel("Cantidad")->labels($combustibles->pluck('name'))->dimensions(1000, 500)->responsive(true)->values($values)->title('Vehículos por Combustible');

            return view('admin.reportes.layout', ['Chart' => $vehiculosPorCombustible]);
        } else {
            return null;
        }
    }

    public function vehiculos_VehiculosPorCarroceria()
    {
        $carrocerias = vehiculo_carroceria::all();
        if ($carrocerias != null) {
            $values = [];

            foreach ($carrocerias as $carroceria) {
                array_push($values, $carroceria->hasVehiculos()->count());
            }

            $vehiculosPorCarroceria = Charts::database(vehiculo_carroceria::all(), 'pie', 'highcharts')->elementLabel("Cantidad")->labels($carrocerias->pluck('name'))->dimensions(1000, 500)->responsive(true)->values($values)->title('Vehículos por Carrocería');

            return view('admin.reportes.layout', ['Chart' => $vehiculosPorCarroceria]);
        } else {
            return null;
        }
    }

    public function vehiculos_VehiculosPorNivelServicio()
    {
        $servicios = vehiculo_nivel_servicio::all();
        if ($servicios != null) {
            $values = [];

            foreach ($servicios as $servicio) {
                array_push($values, \DB::table('vehiculo')
                                        ->join('vehiculo_empresa_transporte', 'vehiculo.id', '=', 'vehiculo_empresa_transporte.vehiculo_id')
                                        ->join('vehiculo_nivel_servicio', 'vehiculo_empresa_transporte.nivel_servicio_id', '=', 'vehiculo_nivel_servicio.id')
                                        ->where('vehiculo_nivel_servicio.name',$servicio->name)->count()
                );
            }

            $vehiculosPorNivelServicio = Charts::database(vehiculo_nivel_servicio::all(), 'pie', 'highcharts')->elementLabel("Cantidad")->labels($servicios->pluck('name'))->dimensions(1000, 500)->responsive(true)->values($values)->title('Vehículos por Nivel de Servicio');

            return view('admin.reportes.layout', ['Chart' => $vehiculosPorNivelServicio]);
        } else {
            return null;
        }
    }

    public function vehiculos_VehiculosPorServicio()
    {
        $servicios = vehiculo_servicio::all();
        if ($servicios != null) {
            $values = [];

            foreach ($servicios as $servicio) {
                array_push($values,$servicio->hasVehiculos()->count());
            }

            $vehiculosPorServicio = Charts::database(vehiculo_servicio::all(), 'pie', 'highcharts')->elementLabel("Cantidad")->labels($servicios->pluck('name'))->dimensions(1000, 500)->responsive(true)->values($values)->title('Vehículos por Servicio');

            return view('admin.reportes.layout', ['Chart' => $vehiculosPorServicio]);
        } else {
            return null;
        }
    }

    public function misPQR_asignadasGeneralCoEx()
    {
        $currentYear = date(Setting::get('vigencia'));
        $month = [];
        for($i=1;$i<=12;$i++){
            array_push($month, \DB::table('gd_pqr')->whereYear('gd_pqr.created_at',Setting::get('vigencia'))->whereMonth('gd_pqr.created_at', $i)->where('gd_pqr.tipo_pqr', 'CoEx')
                ->join('gd_pqr_asignacion', 'gd_pqr.id', '=', 'gd_pqr_asignacion.gd_pqr_id')
                ->where('gd_pqr_asignacion.usuario_asignado_id', '=', auth()->user()->id)
                ->where('gd_pqr_asignacion.estado', '=','1')
                ->count());
        }       

        $chart = Charts::multi('bar', 'highcharts')
            ->title("Correspondencia Externa Asignada")
            ->elementLabel("Total")
            ->responsive(true)
            ->template("material")
            ->legend(true)
            ->dimensions(1000, 500)
            ->labels(['Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'])
            ->dataset('Total', $month);

        return view('admin.reportes.layout', ['Chart' => $chart]);
    }

    public function misPQR_asignadasGeneralCoIn()
    {
        $currentYear = date(Setting::get('vigencia'));
        $month = [];
        for($i=1;$i<=12;$i++){
            array_push($month, \DB::table('gd_pqr')->whereYear('gd_pqr.created_at',Setting::get('vigencia'))->whereMonth('gd_pqr.created_at', $i)->where('gd_pqr.tipo_pqr', 'CoIn')
                ->join('gd_pqr_asignacion', 'gd_pqr.id', '=', 'gd_pqr_asignacion.gd_pqr_id')
                ->join('gd_pqr_peticionario', 'gd_pqr.id', '=', 'gd_pqr_peticionario.gd_pqr_id')
                ->where('gd_pqr_asignacion.usuario_asignado_id', auth()->user()->id)
                ->where('gd_pqr_asignacion.estado', 1)
                ->where('gd_pqr_peticionario.funcionario_id', '<>', auth()->user()->id)
                ->count());
        }       

        $chart = Charts::multi('bar', 'highcharts')
            ->title("Correspondencia Interna Asignada")
            ->elementLabel("Total")
            ->responsive(true)
            ->template("material")
            ->legend(true)
            ->dimensions(1000, 500)
            ->labels(['Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'])
            ->dataset('Total', $month);

        return view('admin.reportes.layout', ['Chart' => $chart]);
    }

    public function misPQR_radicadasGeneralPorTipo()
    {
        $currentYear = date(Setting::get('vigencia'));
        $data = [];
        $month = [];
        $tipos = ['CoIn', 'CoSa'];
        foreach($tipos as $tipo){
            for($i=1;$i<=12;$i++){
                array_push($month, \DB::table('gd_pqr')->whereYear('gd_pqr.created_at',Setting::get('vigencia'))->whereMonth('gd_pqr.created_at', $i)->where('gd_pqr.tipo_pqr', $tipo)
                    ->join('gd_pqr_peticionario', 'gd_pqr.id', '=', 'gd_pqr_peticionario.gd_pqr_id')
                    ->where('gd_pqr_peticionario.funcionario_id', auth()->user()->id)
                    ->count());
            }
            array_push($data, ['tipo'=>$tipo,'data'=>$month]);
            $month = [];
        }        

        $chart = Charts::multi('bar', 'highcharts')
            ->title("Correspondencia General Radicada por Tipo")
            ->elementLabel("Total")
            ->responsive(true)
            ->template("material")
            ->legend(true)
            ->dimensions(1000, 500)
            ->labels(['Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'])
            ->dataset($data[0]['tipo'], $data[0]['data'])
            ->dataset($data[1]['tipo'], $data[1]['data']);

        return view('admin.reportes.layout', ['Chart' => $chart]);
    }

    public function misPQR_respondidasGeneralCoEx()
    {
        $currentYear = date(Setting::get('vigencia'));
        $data = [];
        $aTiempo = [];
        $fueraTiempo = [];
        $vencidas = [];
        for($i=1;$i<=12;$i++){
            $pqrs = gd_pqr::where('tipo_pqr', 'CoEx')
            ->whereYear('created_at',Setting::get('vigencia'))
            ->whereMonth('created_at', $i)
            ->doesntHave('hasAnulacion')
            ->has('hasRespuestas')
            ->whereHas('hasAsignaciones', function($query){
                $query->where('usuario_asignado_id', auth()->user()->id)->where('responsable', 1)->where('estado', 1);        
            })
            ->whereHas('hasClase', function($query2){
                $query2->where('required_answer', 'SI');
            })
            ->get();            
            $pqrs = $pqrs->filter(function ($value){
                return $value->hasRespuestas()->first()->created_at <= $value->limite_respuesta;
            });
            
            array_push($aTiempo, $pqrs->count());

            $pqrs = gd_pqr::where('tipo_pqr', 'CoEx')
            ->whereYear('created_at',Setting::get('vigencia'))
            ->whereMonth('created_at', $i)
            ->doesntHave('hasAnulacion')
            ->has('hasRespuestas')
            ->whereHas('hasAsignaciones', function($query){
                $query->where('usuario_asignado_id', auth()->user()->id)->where('responsable', 1)->where('estado', 1);        
            })
            ->whereHas('hasClase', function($query2){
                $query2->where('required_answer', 'SI');
            })
            ->get();
            $pqrs = $pqrs->filter(function ($value, $key){
                return $value->hasRespuestas()->first()->created_at > $value->limite_respuesta;
            });
            array_push($fueraTiempo, $pqrs->count());
                
            $pqrs = gd_pqr::where('tipo_pqr', 'CoEx')
            ->whereYear('created_at',Setting::get('vigencia'))
            ->whereMonth('created_at', $i)
            ->whereDate('limite_respuesta', '<', date('Y-m-d'))
            ->doesntHave('hasAnulacion')
            ->doesntHave('hasRespuestas')
            ->whereHas('hasAsignaciones', function($query){
                $query->where('usuario_asignado_id', auth()->user()->id)->where('responsable', 1)->where('estado', 1);        
            })
            ->get();
            
            array_push($vencidas, $pqrs->count());     
        }    
        array_push($data, ['tiempo'=>'A tiempo', 'data'=>$aTiempo]);
        array_push($data, ['tiempo'=>'Fuera de tiempo', 'data'=>$fueraTiempo]);
        array_push($data, ['tiempo'=>'Vencidas', 'data'=>$vencidas]);
              

        $chart = Charts::multi('bar', 'highcharts')
            ->title("Correspondencia Externa Respondida")
            ->elementLabel("Total")
            ->responsive(true)
            ->template("material")
            ->legend(true)
            ->dimensions(1000, 500)
            ->labels(['Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'])
            ->dataset($data[0]['tiempo'], $data[0]['data'])
            ->dataset($data[1]['tiempo'], $data[1]['data'])
            ->dataset($data[2]['tiempo'], $data[2]['data']);

        return view('admin.reportes.layout', ['Chart' => $chart]);
    }

    public function misPQR_respondidasGeneralCoIn()
    {
        $currentYear = date(Setting::get('vigencia'));
        $data = [];
        $aTiempo = [];
        $fueraTiempo = [];
        $vencidas = [];
        for($i=1;$i<=12;$i++){
            $pqrs = gd_pqr::where('tipo_pqr', 'CoIn')
            ->whereYear('created_at',Setting::get('vigencia'))
            ->whereMonth('created_at', $i)
            ->doesntHave('hasAnulacion')
            ->has('hasRespuestas')
            ->whereHas('hasAsignaciones', function($query){
                $query->where('usuario_asignado_id', auth()->user()->id)->where('responsable', 1)->where('estado', 1);        
            })
            ->whereHas('hasPeticionario', function($query2){
                $query2->where('funcionario_id', '<>', auth()->user()->id);
            })
            ->get();            
            $pqrs = $pqrs->filter(function ($value){
                return $value->hasRespuestas()->first()->created_at <= $value->limite_respuesta;
            });
            
            array_push($aTiempo, $pqrs->count());

            $pqrs = gd_pqr::where('tipo_pqr', 'CoIn')
            ->whereYear('created_at',Setting::get('vigencia'))
            ->whereMonth('created_at', $i)
            ->doesntHave('hasAnulacion')
            ->has('hasRespuestas')
            ->whereHas('hasAsignaciones', function($query){
                $query->where('usuario_asignado_id', auth()->user()->id)->where('responsable', 1)->where('estado', 1);        
            })
            ->whereHas('hasPeticionario', function($query2){
                $query2->where('funcionario_id', '<>', auth()->user()->id);
            })
            ->get();
            $pqrs = $pqrs->filter(function ($value, $key){
                return $value->hasRespuestas()->first()->created_at > $value->limite_respuesta;
            });
            array_push($fueraTiempo, $pqrs->count());
                
            $pqrs = gd_pqr::where('tipo_pqr', 'CoIn')
            ->whereYear('created_at',Setting::get('vigencia'))
            ->whereMonth('created_at', $i)
            ->whereDate('limite_respuesta', '<', date('Y-m-d'))
            ->doesntHave('hasAnulacion')
            ->doesntHave('hasRespuestas')
            ->whereHas('hasAsignaciones', function($query){
                $query->where('usuario_asignado_id', auth()->user()->id)->where('responsable', 1)->where('estado', 1);        
            })
            ->whereHas('hasPeticionario', function($query2){
                $query2->where('funcionario_id', '<>', auth()->user()->id);
            })
            ->get();
            
            array_push($vencidas, $pqrs->count());     
        }    
        array_push($data, ['tiempo'=>'A tiempo', 'data'=>$aTiempo]);
        array_push($data, ['tiempo'=>'Fuera de tiempo', 'data'=>$fueraTiempo]);
        array_push($data, ['tiempo'=>'Vencidas', 'data'=>$vencidas]);
              

        $chart = Charts::multi('bar', 'highcharts')
            ->title("Correspondencia Interna Respondida")
            ->elementLabel("Total")
            ->responsive(true)
            ->template("material")
            ->legend(true)
            ->dimensions(1000, 500)
            ->labels(['Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'])
            ->dataset($data[0]['tiempo'], $data[0]['data'])
            ->dataset($data[1]['tiempo'], $data[1]['data'])
            ->dataset($data[2]['tiempo'], $data[2]['data']);

        return view('admin.reportes.layout', ['Chart' => $chart]);
    }

    public function misPQR_AsignadasClases()
    {
        $clases = gd_pqr_clase::orderBy('name')->get();
        if ($clases != null) {
            $values = [];
            $mValues = [];
            $i = 0;
            $totales = [
                0 => [
                    'CoEx' => 0,
                    'CoIn' => 0,
                    'CoSa' => 0
                ],
                1 => [
                    'CoEx' => 0,
                    'CoIn' => 0,
                    'CoSa' => 0
                ],
                2 => [
                    'CoEx' => 0,
                    'CoIn' => 0,
                    'CoSa' => 0
                ],
                3 => [
                    'CoEx' => 0,
                    'CoIn' => 0,
                    'CoSa' => 0
                ],
                4 => [
                    'CoEx' => 0,
                    'CoIn' => 0,
                    'CoSa' => 0
                ],
                5 => [
                    'CoEx' => 0,
                    'CoIn' => 0,
                    'CoSa' => 0
                ],
                6 => [
                    'CoEx' => 0,
                    'CoIn' => 0,
                    'CoSa' => 0
                ],
                7 => [
                    'CoEx' => 0,
                    'CoIn' => 0,
                    'CoSa' => 0
                ],
                8 => [
                    'CoEx' => 0,
                    'CoIn' => 0,
                    'CoSa' => 0
                ],
                9 => [
                    'CoEx' => 0,
                    'CoIn' => 0,
                    'CoSa' => 0
                ],
                10 => [
                    'CoEx' => 0,
                    'CoIn' => 0,
                    'CoSa' => 0
                ],
                11 => [
                    'CoEx' => 0,
                    'CoIn' => 0,
                    'CoSa' => 0
                ],
            ];
            foreach ($clases as $clase) {
                for ($p = 1; $p <= 12; $p++) {
                    $pqrs = \DB::table('gd_pqr')->whereYear('gd_pqr.created_at',Setting::get('vigencia'))
                    ->whereMonth('gd_pqr.created_at', $p)
                    ->join('gd_pqr_asignacion', 'gd_pqr.id', '=', 'gd_pqr_asignacion.gd_pqr_id')
                    ->where('gd_pqr_asignacion.usuario_asignado_id', auth()->user()->id)
                    ->where('gd_pqr_asignacion.responsable', '1')
                    ->where('gd_pqr_asignacion.estado', 1)
                    ->where('gd_pqr.gd_pqr_clase_id', $clase->id)
                    ->get();
                    $mValues['CoEx'][$p - 1] = count($pqrs->filter(function($data){
                        return $data->tipo_pqr == 'CoEx';
                    }));
                    $mValues['CoIn'][$p - 1] = count($pqrs->filter(function($data){
                        return $data->tipo_pqr == 'CoIn';
                    }));
                    $mValues['CoSa'][$p - 1] = count($pqrs->filter(function($data){
                        return $data->tipo_pqr == 'CoSa';
                    }));
                    $totales[$p-1]['CoEx'] = $mValues['CoEx'][$p - 1] + $totales[$p-1]['CoEx'];
                    $totales[$p-1]['CoIn'] = $mValues['CoIn'][$p - 1] + $totales[$p-1]['CoIn'];
                    $totales[$p-1]['CoSa'] = $mValues['CoSa'][$p - 1] + $totales[$p-1]['CoSa'];
                }
                $values[$i] = [$clase->name, $mValues];
                $mValues = null;
                $i = $i + 1;
            }
            return view('admin.reportes.pqr.generalRadicadosMeses', ['clases'=>$values,'totales'=>$totales, 'title'=>'PQR ASIGANADAS POR TIPO Y CLASE'])->render();
        } else {
            return null;
        }
    }

    public function misPQR_respondidasClases()
    {
        $clases = gd_pqr_clase::orderBy('name')->get();
        if ($clases != null) {
            $values = [];
            $mValues = [];
            $i = 0;
            $totales = [
                0 => [
                    'CoEx' => 0,
                    'CoIn' => 0,
                    'CoSa' => 0
                ],
                1 => [
                    'CoEx' => 0,
                    'CoIn' => 0,
                    'CoSa' => 0
                ],
                2 => [
                    'CoEx' => 0,
                    'CoIn' => 0,
                    'CoSa' => 0
                ],
                3 => [
                    'CoEx' => 0,
                    'CoIn' => 0,
                    'CoSa' => 0
                ],
                4 => [
                    'CoEx' => 0,
                    'CoIn' => 0,
                    'CoSa' => 0
                ],
                5 => [
                    'CoEx' => 0,
                    'CoIn' => 0,
                    'CoSa' => 0
                ],
                6 => [
                    'CoEx' => 0,
                    'CoIn' => 0,
                    'CoSa' => 0
                ],
                7 => [
                    'CoEx' => 0,
                    'CoIn' => 0,
                    'CoSa' => 0
                ],
                8 => [
                    'CoEx' => 0,
                    'CoIn' => 0,
                    'CoSa' => 0
                ],
                9 => [
                    'CoEx' => 0,
                    'CoIn' => 0,
                    'CoSa' => 0
                ],
                10 => [
                    'CoEx' => 0,
                    'CoIn' => 0,
                    'CoSa' => 0
                ],
                11 => [
                    'CoEx' => 0,
                    'CoIn' => 0,
                    'CoSa' => 0
                ],
            ];
            foreach ($clases as $clase) {
                for ($p = 1; $p <= 12; $p++) {
                    $pqrs = gd_pqr::whereYear('created_at',Setting::get('vigencia'))
                        ->whereMonth('created_at', $i)
                        ->doesntHave('hasAnulacion')
                        ->has('hasRespuestas')
                        ->whereHas('hasAsignaciones', function($query){
                            $query->where('usuario_asignado_id', auth()->user()->id)->where('responsable', 1)->where('estado', 1);        
                        })
                        ->whereHas('hasPeticionario', function($query2){
                            $query2->where('funcionario_id', '<>', auth()->user()->id);
                        })
                        ->get(); 
                    $mValues['CoEx'][$p - 1] = count($pqrs->filter(function($data){
                        return $data->tipo_pqr == 'CoEx';
                    }));
                    $mValues['CoIn'][$p - 1] = count($pqrs->filter(function($data){
                        return $data->tipo_pqr == 'CoIn';
                    }));
                    $mValues['CoSa'][$p - 1] = count($pqrs->filter(function($data){
                        return $data->tipo_pqr == 'CoSa';
                    }));
                    $totales[$p-1]['CoEx'] = $mValues['CoEx'][$p - 1] + $totales[$p-1]['CoEx'];
                    $totales[$p-1]['CoIn'] = $mValues['CoIn'][$p - 1] + $totales[$p-1]['CoIn'];
                    $totales[$p-1]['CoSa'] = $mValues['CoSa'][$p - 1] + $totales[$p-1]['CoSa'];
                }
                $values[$i] = [$clase->name, $mValues];
                $mValues = null;
                $i = $i + 1;
            }
            return view('admin.reportes.pqr.generalRadicadosMeses', ['clases'=>$values,'totales'=>$totales,'title'=>'PQR RESPONDIDAS POR TIPO Y CLASE'])->render();
        } else {
            return null;
        }
    }

    public function pqr_informeGeneralControlInterno(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'fecha_inicio_submit' => 'required|date',
            'fecha_fin_submit' => 'required|date',
            'tipoPQR' => ['required','string',Rule::in(['CoEx','CoIn','CoSa'])]
        ], [
            'fecha_inicio_submit.required' => 'No se ha especificado la fecha de inicio.',
            'fecha_inicio_submit.data' => 'La fecha de inicio especificada no tiene un formato válido.',
            'fecha_fin_submit.required' => 'No se ha especificado la fecha de finalización.',
            'fecha_fin_submit.date' => 'a fecha de finalización especificada no tiene un formato válido.',
            'tipoPQR.required' => 'No se ha especificado el tipo de PQR.',
            'tipoPQR.string' => 'El tipo de PQR especificado no tiene un formato válido.',
            'tipoPQR.in' => 'El tipo de PQR especificado no tiene un valor válido.'
        ]);

        if ($validator->fails()) {            
            return back()->withErrors($validator->errors()->all());
        }

        if($request->fecha_inicio_submit > $request->fecha_fin_submit){
            return back()->withErrors(['La fecha de inicio no puede ser superior a la fecha de finalización.']);
        }

        return Excel::download(new PqrExports($request->fecha_inicio_submit, $request->fecha_fin_submit, $request->tipoPQR), 'pqrControlInterno-'.$request->tipoPQR.'-'.$request->fecha_inicio_submit.'-a-'.$request->fecha_fin_submit.'.xlsx');        
    }
}