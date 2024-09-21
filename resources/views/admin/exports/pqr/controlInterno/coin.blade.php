<table>
    <thead>
    <tr>
        <th>Radicado</th>
        <th>Fecha Radicado</th>
        <th>Radicado por</th>
        <th>Clase</th>
        <th>Asunto</th>
        <th>Límite Respuesta</th>
        <th>Previo aviso</th>
        <th>Dependencia responsable</th>
        <th>Funcionario responsable</th>
        <th>Estado respuesta</th>
        <th>Respuestas</th>
        <th>Fecha respuesta (antiguedad)</th>
    </tr>
    </thead>
    <tbody>
    @foreach ($pqrs as $pqr)
        <tr>
            <td>{{$pqr->getRadicadoEntrada->numero}}</td>
            <td>{{$pqr->getRadicadoEntrada->created_at->format('Y-m-d H:i:s')}}</td>
            <td>{{$pqr->hasPeticionario->couldHaveFuncionario->name}}</td>
            <td>{{$pqr->hasClase->name}}</td>
            <td>{{$pqr->asunto}}</td>
            <td>{{$pqr->limite_respuesta}}</td>
            <td>{{$pqr->previo_aviso}}</td>
            @if($pqr->hasResponsable() != null)
            <td>{{$pqr->hasResponsable()->hasDependencia->name}}</td>
            <td>{{$pqr->hasResponsable()->hasUsuarioAsignado->name}}</td>
            @else 
            <td></td>
            <td></td>
            @endif
            @if($pqr->limite_respuesta != null)
            <td>@if($pqr->hasRespuestas->count() > 0) Respondido @else Sin responder @endif</td>
            <td>
                @foreach ($pqr->hasRespuestas as $respuesta)
                    @if ($loop->last)
                    {{$respuesta->getRadicadoSalida->numero}}
                    @else
                    {{$respuesta->getRadicadoSalida->numero.','}}
                    @endif
                @endforeach
            </td> 
            <td>@if($pqr->hasRespuestas->count() > 0) {{$pqr->hasRespuestas()->orderBy('created_at', 'asc')->first()->created_at}} @endif</td>
            @else
            <td>No requiere</td>
            <td>No requiere</td>
            <td>No requiere</td>
            @endif
        </tr>
    @endforeach
    </tbody>
</table>