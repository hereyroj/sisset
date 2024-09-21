<div id="modalListadoNotificaciones">
    <div class="cabecera-tabla">
        <div>
            <button type="button" class="btn btn-primary btn-actualizar btn-md" onclick="recargarListadoNotificaciones({{$mandamiento->id}});">
                <i class="fas fa-sync"></i> Actualizar
            </button>
        </div>
    </div>
    <table class="table table-striped ">
        <thead>
            <tr>
                <th>Tipo</th>
                <th>Consecutivo</th>
                <th>Fecha</th>
                <th>Fecha max. presentación</th>
                <th>Estado</th>
                <th>Medio</th>
                <th>Empresa</th>
                <th>Número guía</th>
                <th>Notificación</th>
                <th>Pantallazo RUNT</th>
                <th>Acción</th>
            </tr>        
        </thead>
        <tbody>
            @foreach($mandamiento->hasNotificaciones as $notificacion)
            <tr>
                <td>{{$notificacion->hasTipoNotificacion->name}}</td>     
                <td>{{$notificacion->consecutivo}}</td>    
                <td>{{$notificacion->fecha_notificacion}}</td>    
                <td>{{$notificacion->fecha_max_presentacion}}</td>    
                <td>
                    @if($notificacion->hasEntrega)
                    <button type="button" class="btn btn-primary btn-sm" onclick="verEntrega({{$notificacion->hasEntrega->id}})">Ver Entrega</button>
                    @elseif($notificacion->hasDevolucion)
                    <button type="button" class="btn btn-warning btn-sm" onclick="verDevolucion({{$notificacion->hasDevolucion->id}})">Ver Devolución</button>
                    @else 
                    <button type="button" class="btn btn-primary btn-sm" onclick="nuevaEntrega({{$notificacion->id}})">R. Entrega</button>
                    <button type="button" class="btn btn-warning btn-sm" onclick="nuevaDevolucion({{$notificacion->id}})">R. Devolución</button>
                    @endif
                </td>    
                <td>{{$notificacion->hasMedio->hasMedioNotificacion->name}}</td>
                <td>@if($notificacion->hasMedio->hasEmpresa != null) {{$notificacion->hasMedio->hasEmpresa->name}} @endif</td>    
                <td>{{$notificacion->hasMedio->numero_guia}}</td>
                <td><a href="{{url('admin/coactivo/mandamientos/obtenerMandamientoNotificacion/'.$notificacion->id)}}" class="btn btn-outline-secondary">Ver</a></td>
                <td><a href="{{url('admin/coactivo/mandamientos/obtenerPantalalzoRuntMandamientoNotificacion/'.$notificacion->id)}}" class="btn btn-outline-secondary">Ver</a></td>
                <td><button type="button" class="btn btn-secondary" onclick="editarNotificacion({{$notificacion->id}})">Editar</button></td>
            </tr>
            @endforeach
        </tbody>
    </table>    
</div>
<script type="text/javascript" src="{{asset('js/coactivo/mandamientos/listadoNotificaciones.js')}}"></script>