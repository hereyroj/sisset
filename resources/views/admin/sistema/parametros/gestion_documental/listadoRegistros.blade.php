<div class="table-responsive">
    <table class="table table-striped">
        <thead>
        <tr>
            <th>Vigencia</th>
            <th>Inicial Radicado Entrada</th>
            <th>Inicial Radicado Salida</th>
            <th>Incial Sanciones</th>
            <th>Encabezado</th>
            <th>Pie de página</th>
            <th>Acciones</th>
        </tr>
        </thead>
        <tbody>
        @foreach($registros as $registro)
            <tr>
                <td>
                    {{$registro->hasVigencia->vigencia}}
                </td>
                <td>
                    {{$registro->radicado_entrada_consecutivo}}
                </td>
                <td>
                    {{$registro->radicado_salida_consecutivo}}
                </td>
                <td>
                    {{$registro->sancion_consecutivo}}
                </td>
                <td>
                    <img src="{{asset('storage/parametros/gd/'.$registro->encabezado_documento)}}" class="img-thumbnail">
                </td>
                <td>
                    <img src="{{asset('storage/parametros/gd/'.$registro->pie_documento)}}" class="img-thumbnail">
                </td>
                <td>
                    <button type="button" class="btn btn-secondary" onclick="editarRegistro({{$registro->id}})">Editar</button>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>