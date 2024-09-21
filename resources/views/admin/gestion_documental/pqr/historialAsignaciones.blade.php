<ul class="list-group">
    @foreach($asignaciones as $asignacion)
        <li class="list-group-item" @if($asignacion->fecha_reasignacion != null) style="background-color: #990000; color:#fff;" @endif>
            Dependencia:<br><strong>{{$asignacion->hasDependencia->name}}</strong><br>
            Asignado a:<br><strong>{{$asignacion->hasUsuarioAsignado->name}}</strong><br>
            Responsable:<br><strong>@if($asignacion->responsable == 1) SI @else NO @endif</strong><br>
            El: <br><strong>{{$asignacion->created_at}}</strong>
            @if($asignacion->fecha_reasignacion != null)
                <br>Fecha reasignación:<br><strong>{{$asignacion->fecha_reasignacion}}</strong><br>
                Motivo:<br><strong>{{$asignacion->descripcion_reasignacion}}</strong>
            @endif
        </li>
    @endforeach
</ul>