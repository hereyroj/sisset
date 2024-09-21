<div class="table-responsive">
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Nombre</th>
                <th>Placa consecutiva</th>
                <th>Clases de vehículos</th>
                <th>Fecha creación</th>
                <th>Última actualización</th>
                <th>Acción</th>
            </tr>
        </thead>
        <tbody>
            @foreach($servicios as $servicio)
            <tr @if($servicio->trashed()) class="danger" @endif>
                <td>
                    {{$servicio->name}}
                </td>
                <td>{{$servicio->placa_consecutivo}}</td>
                <th>
                    @foreach($servicio->hasClasesVinculadas as $clase)
                    <span class="badge badge-pill badge-primary">{{$clase->name}}</span> @endforeach
                </th>
                <td>{{$servicio->created_at}}</td>
                <td>{{$servicio->updated_at}}</td>
                <td>
                    <div class="btn-group" role="group" aria-label="...">
                        <button type="button" class="btn btn-secondary btn-editar-servicio" onclick="editarServicio({{$servicio->id}});">Editar</button>                    @if($servicio->trashed())
                        <button type="button" class="btn btn-secondary btn-activar-servicio" onclick="activarServicio({{$servicio->id}});">Activar</button>                    @else
                        <button type="button" class="btn btn-secondary btn-eliminar-servicio" onclick="eliminarServicio({{$servicio->id}});">Eliminar</button>                    @endif
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <div class="text-center">
        {{$servicios->links('vendor.pagination.bootstrap-4')}}
    </div>
</div>