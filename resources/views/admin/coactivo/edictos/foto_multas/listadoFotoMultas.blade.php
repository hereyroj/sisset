<div class="listadoFotoMultas">
    <div class="cabecera-tabla">
        <div>
            <button type="button" class="btn btn-primary btn-actualizar btn-md" id="actualizarListadoEdictos" onclick="obtenerFotoMultas();">
                <i class="fas fa-sync"></i> Actualizar
            </button>
        </div>
        <div class="field-search input-group">
            <input type="text" name="filtrarBusqueda" id="filtrarBusqueda" placeholder="Buscar por nombre o cedula" @if(isset($parametro))
                value="{{$parametro}}" @endif>
            <button type="button" class="btn-buscar" onclick="filtrarBusqueda();">
                <i class="fas fa-search"></i>
            </button>
            <button type="button" class="btn-restaurar" onclick="obtenerFotoMultas();">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div>
            <button type="button" class="btn btn-primary btn-actualizar btn-md" onclick="nuevaNotificacion();">
                <span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Nueva
            </button>
        </div>
    </div>
    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Fecha de publicación</th>
                    <th>Cedula</th>
                    <th>Nombre</th>
                    <th>Edicto</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($fotoMultas as $fotoMulta)
                <tr @if($fotoMulta->pathArchive === '0000' || strpos($fotoMulta->pathArchive, 'drive') !== false) class="danger" @endif>
                    <td>{{$fotoMulta->publication_date}}</td>
                    <td>{{$fotoMulta->cc}}</td>
                    <td>{{$fotoMulta->name}}</td>
                    <td>
                        @if($fotoMulta->pathArchive !== '0000' && strpos($fotoMulta->pathArchive, 'http') === false)
                        <a href="{{url('servicios/edictos/fotomultas/ver/'.$fotoMulta->id)}}" class="btn btn-secondary btn-block">Ver</a>                    @endif
                    </td>
                    <td>
                        <div class="btn-group" role="group" aria-label="...">
                            <button type="button" class="btn btn-secondary" onclick="editarFotoMulta({{$fotoMulta->id}});">Editar</button>
                            <button type="button" class="btn btn-secondary" onclick="eliminarFotoMulta({{$fotoMulta->id}});">Eliminar</button>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <div class="text-center">
            {{$fotoMultas->links('vendor.pagination.bootstrap-4')}}
        </div>
    </div>    
</div>