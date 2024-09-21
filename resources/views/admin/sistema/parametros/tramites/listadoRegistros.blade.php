<div class="table-responsive">
    <table class="table table-striped">
        <thead>
        <tr>
            <th>Vigencia</th>
            <th>Inicial Radicado</th>
            <th>Hora inicio atención</th>
            <th>Hora fin atención</th>
            <th>Re-llamado</th>
            <th>Preferencial</th>
            <th>Transferencia</th>
            <th>Tiempo de espera</th>
            <th>Logo etiqueta turno</th>
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
                    {{$registro->radicado_tramite_consecutivo}}
                </td>
                <td>
                    {{$registro->inicio_atencion}}
                </td>
                <td>
                    {{$registro->fin_atencion}}
                </td>
                <td>
                    {{$registro->turno_rellamado}}
                </td>
                <td>
                    {{$registro->turno_preferencial}}
                </td>
                <td>
                    {{$registro->turno_transferencia}}
                </td>
                <td>
                    {{$registro->turno_tiempo_espera}}
                </td>
                <td>
                    <img src="{{asset('storage/parametros/tramites/'.$registro->turno_logo)}}" class="rounded img-fluid" style="height: 140px; margin: 0 auto;">
                </td>
                <td>
                    <button type="button" class="btn btn-secondary" onclick="editarRegistro({{$registro->id}})">Editar</button>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>