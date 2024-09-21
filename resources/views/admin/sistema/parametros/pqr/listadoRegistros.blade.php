<div class="table-responsive">
    <table class="table table-striped">
        <thead>
        <tr>
            <th>Vigencia</th>
            <th>Inicial Radicado Entrada</th>
            <th>Inicial Radicado Salida</th>
            <th>Ciclo días comprobación</th>
            <th>Permitir editar resueltos</th>
            <th>Logo etiqueta</th>
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
                    {{$registro->dias_previo_aviso}}
                </td>
                <td>
                    {{$registro->editar_pqr_resuelto}}
                </td>
                <td>
                    <img src="{{asset('storage/parametros/pqr/'.$registro->logo_pqr_radicado)}}" class="rounded img-fluid" style="height: 140px; margin: 0 auto;">
                </td>
                <td>
                    <button type="button" class="btn btn-secondary" onclick="editarRegistro({{$registro->id}})">Editar</button>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>