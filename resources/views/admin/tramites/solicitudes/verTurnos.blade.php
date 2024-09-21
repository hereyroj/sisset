<div class="table-responsive">
    <table class="table table-striped">
        <thead>
        <tr>
            <th rowspan="2">Turno</th>
            <th rowspan="2">Solicitante</th>
            <th colspan="3">Fecha y Hora de Atención</th>
            <th rowspan="2">Total Tiempo (minutos)</th>
            <th colspan="2">Atendido por</th>
            <th colspan="2">Re-llamado</th>
            <th>Anulación</th>
            <th>Vencimiento</th>
        </tr>
        <tr>
            <th>Fecha</th>
            <th>Llamado</th>
            <th>Finalizado</th>
            <th>Funcionario</th>
            <th>Observación</th>
            <th>Fecha y Hora</th>
            <th>Por</th>
            <th>Fecha y Hora</th>
            <th>Fecha y Hora</th>
        </tr>
        </thead>
        <tbody>
        @foreach($tramite->hasTurnos as $turno)
            <tr>
                <td>{{$turno->turno}}</td>
                <td><button class="btn btn-danger" onclick="editarSolicitante({{$turno->id}});"><i class="fas fa-user"></i> {{$turno->hasUsuarioSolicitante->nombre_usuario}}</button></td>
                <td>{{$turno->created_at}}</td>
                @if($turno->fecha_llamado != null)
                    <td>{{$turno->fecha_llamado->format('H:i:s')}}</td>
                @else
                    <td></td>
                @endif
                @if($turno->fecha_atencion != null)
                    <td>{{$turno->fecha_atencion->format('H:i:s')}}</td>
                @else
                    <td></td>
                @endif
                @if($turno->fecha_llamado != null && $turno->fecha_atencion != null)
                    <td>{{\Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $turno->fecha_llamado)->diffInMinutes(\Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $turno->fecha_atencion))}}</td>
                @else
                    <td></td>
                @endif
                @if($turno->fecha_atencion != null)
                    <td>{{$turno->hasAtencion->hasFuncionario->name}}</td>
                    <td>{{$turno->hasAtencion->observacion}}</td>
                @else
                    <td></td>
                    <td></td>
                @endif
                @if($turno->fecha_rellamado != null)
                    <td>{{$turno->fecha_rellamado}}</td>
                    <td>{{$turno->hasFuncionarioReLlamado->name}}</td>
                @else
                    <td></td>
                    <td></td>
                @endif
                @if($turno->fecha_anulacion != null)
                    <td>{{$turno->fecha_anulacion->format('H:i:s')}}</td>
                @else
                    <td></td>
                @endif
                @if($turno->fecha_vencimiento != null)
                    <td>{{$turno->fecha_vencimiento->format('H:i:s')}}</td>
                @else
                    <td></td>
                @endif
            </tr>
        @endforeach
        </tbody>
    </table>
</div>