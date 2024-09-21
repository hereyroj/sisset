<div class="table-responsive">
    <table class="table table-striped">
        <thead>
            <tr>
                <th>
                    <input type="checkbox" name="seleccion" id="seleccion" />
                </th>
                <th>Placa</th>
                <th>Clases vehículos</th>
                <th>Servicio</th>
                <th>Fecha Pre-asignación</th>
                <th>Fecha matricula</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($placas as $placa)
            <tr <?php if($placa->hasPreAsignacionActiva() != null){ $fecha_preasignacion = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $placa->hasPreAsignacionActiva()->pivot->fecha_preasignacion);
                if($fecha_preasignacion->addDays(60)
                < \Carbon\Carbon::now()){ ?>
                    class="alert-danger"
                    <?php
                    }
                }
                ?>
                        >
                        <td>
                            <input type="checkbox" id="seleccionados[]" name="seleccionados[]" value="{{$placa->id}}">
                        </td>
                        <td>
                            {{$placa->name}}
                        </td>
                        <td>
                            @foreach($placa->hasVehiculosClases as $clase)
                            <span class="badge badge-pill badge-primary">{{$clase->name}}</span> @endforeach
                        </td>
                        <td>
                            {{$placa->hasVehiculoServicio->name}}
                        </td>
                        <td>
                            @if($placa->hasPreAsignacionActiva() != null) {{$placa->hasPreAsignacionActiva()->pivot->fecha_preasignacion}} @endif
                        </td>
                        <td>
                            @if($placa->estaMatriculado() != null) {{$placa->estaMatriculado()->pivot->fecha_matricula}} @endif
                        </td>
                        <td>
                            @if($placa->hasPreAsignacionActiva() == null && $placa->estaMatriculado() == null)
                            <button type="button" class="btn btn-secondary" onclick="editarPlaca({{$placa->id}});">Editar</button>                        @elseif($placa->hasPreAsignacionActiva() != null) @if($placa->estaMatriculado() == null)
                            <button type="button" class="btn btn-secondary" onclick="liberarPlaca({{$placa->id}});">Liberar</button>                        @endif @endif
                        </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <div class="text-center">
        {{$placas->links('vendor.pagination.bootstrap-4')}}
    </div>
</div>
<div>
    Para todos los seleccionados:
    <button type="button" class="btn btn-secondary" onclick="liberacionPlacas();">Liberar</button>
</div>