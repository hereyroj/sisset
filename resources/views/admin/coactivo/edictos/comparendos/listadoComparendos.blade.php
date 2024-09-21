<div class="listadoComparendos">
    <div class="cabecera-tabla">
        <div>
            <button type="button" class="btn btn-primary btn-actualizar btn-md" id="actualizarListadoComparendos" onclick="obtenerComparendos();">
                <i class="fas fa-sync"></i> Actualizar
            </button>
        </div>
        <div class="field-search input-group">
            <input type="text" name="filtrarBusqueda" id="filtrarBusqueda" placeholder="Buscar por nombre o cedula" @if(isset($parametro))
                value="{{$parametro}}" @endif>
            <button type="button" class="btn-buscar" onclick="filtrarBusqueda();">
                <i class="fas fa-search"></i>
            </button>
            <button type="button" class="btn-restaurar" onclick="obtenerComparendos();">
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
                @foreach($comparendos as $comparendo)
                <tr @if($comparendo->pathArchive === '0000' || strpos($comparendo->pathArchive, 'drive') !== false) class="danger" @endif>
                    <td>{{$comparendo->publication_date}}</td>
                    <td>{{$comparendo->cc}}</td>
                    <td>{{$comparendo->name}}</td>
                    <td>
                        @if($comparendo->pathArchive !== '0000' && strpos($comparendo->pathArchive, 'http') === false)
                        <a href="{{url('servicios/edictos/comparendos/ver/'.$comparendo->id)}}" class="btn btn-secondary btn-block">Ver</a>                    @endif
                    </td>
                    <td>
                        <div class="btn-group" role="group" aria-label="...">
                            <button type="button" class="btn btn-secondary" onclick="editarComparendo({{$comparendo->id}});">Editar</button>
                            <button type="button" class="btn btn-secondary" onclick="eliminarComparendo({{$comparendo->id}});">Eliminar</button>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <div class="text-center">
            {{$comparendos->links('vendor.pagination.bootstrap-4')}}
        </div>
    </div>    
</div>