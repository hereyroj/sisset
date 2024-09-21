@extends('layouts.app')

@section('meta')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Bienvenidos - {{Setting::get('empresa_sigla')}}</title>
@endsection

@section('styles')
    <link href="https://fonts.googleapis.com/css?family=Roboto+Condensed" rel="stylesheet">

    <style>
        body {
            background-color: #DADADA;
        }

        .title {
            font-family: 'Roboto Condensed', sans-serif;
        }

        .pagination > .active > a, .pagination > .active > a:focus, .pagination > .active > a:hover, .pagination > .active > span, .pagination > .active > span:focus, .pagination > .active > span:hover {
            background-color: #286090;
            border-color: #286090;
            color: #fff;
        }

        .pagination > li > a, .pagination > li > span {
            color: #286090;
        }

        #ultimasPublicaciones{
            min-height: 400px; 
            display: flex;
        }

        #ultimasPublicaciones > div{
            float: left;
            margin-bottom: 1em;
            margin-right: 1em;
        }

        .card-title{
            font-weight: bold;
            color: #000;
        }

        .card-img-top{
            width: 100%;
            height: 20em;
            margin-left: auto;
            margin-right: auto;
            position: relative;
            display: block;
        }

        .card img{
            padding: 0.25rem;
        }

        .carousel-inner{
            overflow: auto;
        }

        .servicios{
            margin: 0.25em;
        }

        .servicios a{
            min-width: 5em;
            border-top-left-radius: 0;
            border-bottom-left-radius: 0;
        }

        @media screen and (min-width: 768px) {
            .carousel-inner .active,
            .carousel-inner .active+.carousel-item {
                display: block;
            }
            .carousel-inner .carousel-item.active:not(.carousel-item-right):not(.carousel-item-left),
            .carousel-inner .carousel-item.active:not(.carousel-item-right):not(.carousel-item-left)+.carousel-item {
                -webkit-transition: none;
                transition: none;
            }
            .carousel-inner .carousel-item-next,
            .carousel-inner .carousel-item-prev {
                position: relative;
                -webkit-transform: translate3d(0, 0, 0);
                transform: translate3d(0, 0, 0);
            }
            .carousel-inner .active.carousel-item+.carousel-item+.carousel-item+.carousel-item {
                position: absolute;
                top: 0;
                right: -50%;
                z-index: -1;
                display: block;
                visibility: visible;
            }
            /* left or forward direction */
            .active.carousel-item-left+.carousel-item-next.carousel-item-left,
            .carousel-item-next.carousel-item-left+.carousel-item {
                position: relative;
                -webkit-transform: translate3d(-100%, 0, 0);
                transform: translate3d(-100%, 0, 0);
                visibility: visible;
            }
            /* farthest right hidden item must be abso position for animations */
            .carousel-inner .carousel-item-prev.carousel-item-right {
                position: absolute;
                top: 0;
                left: 0;
                z-index: -1;
                display: block;
                visibility: visible;
            }
            /* right or prev direction */
            .active.carousel-item-right+.carousel-item-prev.carousel-item-right,
            .carousel-item-prev.carousel-item-right+.carousel-item {
                position: relative;
                -webkit-transform: translate3d(100%, 0, 0);
                transform: translate3d(100%, 0, 0);
                visibility: visible;
                display: block;
                visibility: visible;
            }
            }

            /* Desktop and up */

            @media screen and (min-width: 992px) {
            .carousel-inner .active,
            .carousel-inner .active+.carousel-item,
            .carousel-inner .active+.carousel-item+.carousel-item {
                display: block;
            }
            .carousel-inner .carousel-item.active:not(.carousel-item-right):not(.carousel-item-left),
            .carousel-inner .carousel-item.active:not(.carousel-item-right):not(.carousel-item-left)+.carousel-item,
            .carousel-inner .carousel-item.active:not(.carousel-item-right):not(.carousel-item-left)+.carousel-item+.carousel-item {
                -webkit-transition: none;
                transition: none;
            }
            .carousel-inner .carousel-item-next,
            .carousel-inner .carousel-item-prev {
                position: relative;
                -webkit-transform: translate3d(0, 0, 0);
                transform: translate3d(0, 0, 0);
            }
            .carousel-inner .active.carousel-item+.carousel-item+.carousel-item+.carousel-item {
                position: absolute;
                top: 0;
                right: -33.3333%;
                z-index: -1;
                display: block;
                visibility: visible;
            }
            /* left or forward direction */
            .active.carousel-item-left+.carousel-item-next.carousel-item-left,
            .carousel-item-next.carousel-item-left+.carousel-item,
            .carousel-item-next.carousel-item-left+.carousel-item+.carousel-item,
            .carousel-item-next.carousel-item-left+.carousel-item+.carousel-item+.carousel-item {
                position: relative;
                -webkit-transform: translate3d(-100%, 0, 0);
                transform: translate3d(-100%, 0, 0);
                visibility: visible;
            }
            /* farthest right hidden item must be abso position for animations */
            .carousel-inner .carousel-item-prev.carousel-item-right {
                position: absolute;
                top: 0;
                left: 0;
                z-index: -1;
                display: block;
                visibility: visible;
            }
            /* right or prev direction */
            .active.carousel-item-right+.carousel-item-prev.carousel-item-right,
            .carousel-item-prev.carousel-item-right+.carousel-item,
            .carousel-item-prev.carousel-item-right+.carousel-item+.carousel-item,
            .carousel-item-prev.carousel-item-right+.carousel-item+.carousel-item+.carousel-item {
                position: relative;
                -webkit-transform: translate3d(100%, 0, 0);
                transform: translate3d(100%, 0, 0);
                visibility: visible;
                display: block;
                visibility: visible;
            }
        }
    </style>    
@endsection

@section('content')
    <div class="row" style="margin-bottom: 2em;">
        <div class="col-lg-12">
            <div class="dashboard-box">
                <div class="page-header" style="margin: 0; top: 0; padding-bottom: 0;">
                    <h2>Últimas publicaciones <a href="{{url('/posts')}}" class="btn btn-outline-primary">Ver todas</a></h2>
                </div>                
                <div class="body-box" >
                    <div id="sliderUltimasPublicaciones" class="carousel slide" data-ride="carousel">
                        <div class="carousel-inner row w-100 mx-auto" id="ultimasPublicaciones">

                        </div>
                    </div>
                    <div class="container">
                        <div class="row">
                            <div class="col-12 text-center mt-4">
                                <a class="btn btn-outline-secondary mx-1 prev" href="javascript:void(0)" title="Anterior">
                                    <i class="fa fa-lg fa-chevron-left"></i>
                                </a>
                                <a class="btn btn-outline-secondary mx-1 next" href="javascript:void(0)" title="Siguiente">
                                    <i class="fa fa-lg fa-chevron-right"></i>
                                </a>                        
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row" style="margin-bottom: 2em;">
        <div class="col-md-4 col-xs-12 col-xs-12">
            <div class="dashboard-box">
                <div class="page-header" style="margin: 0; top: 0; padding-bottom: 0;">
                    <h2>Servicios institucionales</h2>
                </div>
                <div class="body-box d-flex align-content-start flex-wrap row-eq-height flex-row">                    
                    <div class="flex-column flex-fill">
                        <div class="card flex-md-row servicios shadow-sm h-md-250">
                            <div class="card-body d-flex flex-column align-items-start">
                                <h3 class="mb-0">
                                    <strong class="d-inline-block mb-2 text-primary">Acuerdos de pago</strong>
                                </h3>
                                <p class="card-text mb-auto">Consulte aquí su historial de acuerdos de pago.</p>
                            </div>
                            <a href="{{url('servicios/inspeccion/acuerdoPago/index')}}" class="btn btn-primary d-flex align-items-center justify-content-center">Ir</a>
                        </div>
                        <div class="card flex-md-row servicios shadow-sm h-md-250">
                            <div class="card-body d-flex flex-column align-items-start">                            
                                <h3 class="mb-0">
                                    <strong class="d-inline-block mb-2 text-primary">Comparendos</strong>
                                </h3>
                                <p class="card-text mb-auto">Consulte aquí su historial de comparendos.</p>
                            </div>
                            <a href="{{url('servicios/inspeccion/comparendos/index')}}" class="btn btn-primary d-flex align-items-center justify-content-center">Ir</a>
                        </div>
                        <div class="card flex-md-row servicios shadow-sm h-md-250">
                            <div class="card-body d-flex flex-column align-items-start">                            
                                <h3 class="mb-0">
                                    <strong class="d-inline-block mb-2 text-primary">Estado de cuenta</strong>
                                </h3>
                                <p class="card-text mb-auto">Consulte aquí los estado de cuenta de su(s) vehículo(s).</p>
                            </div>
                            <a href="{{url('servicios/vehiculo/estadoCuenta/index')}}" class="btn btn-primary d-flex align-items-center justify-content-center">Ir</a>
                        </div>
                        <div class="card flex-md-row servicios shadow-sm h-md-250">
                            <div class="card-body d-flex flex-column align-items-start">                            
                                <h3 class="mb-0">
                                    <strong class="d-inline-block mb-2 text-primary">Inmovilizaciones</strong>
                                </h3>
                                <p class="card-text mb-auto">Consulte aquí si su vehículo ha sido inmovilizado.</p>
                            </div>
                            <a href="{{url('servicios/vehiculo/inmovilizaciones/index')}}" class="btn btn-primary d-flex align-items-center justify-content-center">Ir</a>
                        </div>   
                        <div class="card flex-md-row servicios shadow-sm h-md-250">
                            <div class="card-body d-flex flex-column align-items-start">                            
                                <h3 class="mb-0">
                                    <strong class="d-inline-block mb-2 text-primary">Liquidar acuerdo de pago</strong>
                                </h3>
                                <p class="card-text mb-auto">Liquide aquí sus acuerdos de pago.</p>
                            </div>
                            <a href="{{url('servicios/liquidaciones/acuerdoPago/index')}}" class="btn btn-primary d-flex align-items-center justify-content-center">Ir</a>
                        </div>  
                        <div class="card flex-md-row servicios shadow-sm h-md-250">
                            <div class="card-body d-flex flex-column align-items-start">                            
                                <h3 class="mb-0">
                                    <strong class="d-inline-block mb-2 text-primary">Liquidar comparendo</strong>
                                </h3>
                                <p class="card-text mb-auto">Liquide aquí sus comparendos.</p>
                            </div>
                            <a href="{{url('servicios/liquidaciones/comparendos/index')}}" class="btn btn-primary d-flex align-items-center justify-content-center">Ir</a>
                        </div>
                        <div class="card flex-md-row servicios shadow-sm h-md-250">
                            <div class="card-body d-flex flex-column align-items-start">                            
                                <h3 class="mb-0">
                                    <strong class="d-inline-block mb-2 text-primary">Liquidar impuesto público</strong>
                                </h3>
                                <p class="card-text mb-auto">Liquide aquí sus impuestos de vehículo de servicio público.</p>
                            </div>
                            <a href="{{url('servicios/servicios/liquidaciones/servicioPublico/index')}}" class="btn btn-primary d-flex align-items-center justify-content-center">Ir</a>
                        </div>
                        <div class="card flex-md-row servicios shadow-sm h-md-250">
                            <div class="card-body d-flex flex-column align-items-start">
                                <h3 class="mb-0">
                                    <strong class="d-inline-block mb-2 text-primary">Normatividad</strong>
                                </h3>
                                <p class="card-text mb-auto">Consulte aquí toda la nromatividad relacionada con esta entidad.</p>
                            </div>
                            <a href="{{url('servicios/normativa/index')}}" class="btn btn-primary d-flex align-items-center justify-content-center">Ir</a>
                        </div> 
                        <div class="card flex-md-row servicios shadow-sm h-md-250">
                            <div class="card-body d-flex flex-column align-items-start">
                                <h3 class="mb-0">
                                    <strong class="d-inline-block mb-2 text-primary">Notificaciones por aviso</strong>
                                </h3>
                                <p class="card-text mb-auto">Consulte aquí ha sido notificado por aviso.</p>
                            </div>
                            <a href="{{url('servicios/notificacionesAviso/index')}}" class="btn btn-primary d-flex align-items-center justify-content-center">Ir</a>
                        </div>  
                        <div class="card flex-md-row servicios shadow-sm h-md-250">
                            <div class="card-body d-flex flex-column align-items-start">
                                <h3 class="mb-0">
                                    <strong class="d-inline-block mb-2 text-primary">Pre-Asignaciones</strong>
                                </h3>
                                <p class="card-text mb-auto">Aquí podrá solicitar la pre-asignación de una placa.</p>
                            </div>
                            <a href="{{url('servicios/tramites/preasignaciones/index')}}" class="btn btn-primary d-flex align-items-center justify-content-center">Ir</a>
                        </div>                
                        <div class="card flex-md-row servicios shadow-sm h-md-250">
                            <div class="card-body d-flex flex-column align-items-start">
                                <h3 class="mb-0">
                                    <strong class="d-inline-block mb-2 text-primary">PQRS</strong>
                                </h3>
                                <p class="card-text mb-auto">Aquí podrá radicar su petición, queja, reclamo o sugerencia.</p>
                            </div>
                            <a href="{{url('servicios/pqr/index')}}" class="btn btn-primary d-flex align-items-center justify-content-center">Ir</a>
                        </div>                        
                        <div class="card flex-md-row servicios shadow-sm h-md-250">
                            <div class="card-body d-flex flex-column align-items-start">                            
                                <h3 class="mb-0">
                                    <strong class="d-inline-block mb-2 text-primary">Tarjetas de operación</strong>
                                </h3>
                                <p class="card-text mb-auto">Consulte aquí la tarjeta de operación.</p>
                            </div>
                            <a href="{{url('servicios/to/index')}}" class="btn btn-primary d-flex align-items-center justify-content-center">Ir</a>
                        </div>                  
                    </div>                
                </div>
            </div>
        </div>
        <div class="col-md-4 col-xs-12 col-xs-12">
            <div class="dashboard-box listado-notificaciones-aviso">
                <div class="page-header" style="margin: 0; top: 0; padding-bottom: 0;">
                    <h2>Notificaciones por aviso <a href="{{url('/servicios/notificacionesAviso/index')}}" class="btn btn-outline-primary" style="float:right">Consultar</a></h2>
                </div>
                <div class="body-box"></div>
            </div>
        </div>
        <div class="col-md-4 col-xs-12 col-xs-12">
            <div class="dashboard-box listado-normativas">
                <div class="page-header" style="margin: 0; top: 0; padding-bottom: 0;">
                    <h2>Normatividad <a href="{{url('/servicios/normativas/index')}}" class="btn btn-outline-primary" style="float:right">Consultar</a></h2>
                </div>
                <div class="body-box"></div>
            </div>
        </div>
    </div>  
@endsection

@section('scripts')
<script type="text/javascript" src="{{asset('js/publico/welcome.js')}}"></script>
@endsection