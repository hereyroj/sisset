<div class="table-responsive">
    <table class="table table-striped">
        <thead>
        <tr>
            <th>Vigencia</th>
            <th>Consecutivo inicial</th>
            <th>Marca de agua</th>
            <th>Valor unitario</th>
            <th>Imagen encabezado</th>
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
                    {{$registro->consecutivo_inicial}}
                </td>
                <td>
                    {{$registro->marca_agua}}
                </td>
                <td>
                    {{$registro->valor_unitario}}
                </td>
                <td>
                    <img src="{{asset('storage/parametros/to/'.$registro->imagen_encabezado)}}" class="rounded img-fluid" style="height: 140px; margin: 0 auto;">
                </td>
                <td>
                    <button type="button" class="btn btn-secondary" onclick="editarRegistro({{$registro->id}})">Editar</button>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>