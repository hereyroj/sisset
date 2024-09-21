<table>
        <thead>
        <tr>
            <th>Radicado</th>
            <th>Fecha Radicado</th>
            <th>Radicado por</th>
            <th>Clase</th>
            <th>Asunto</th>
            <th>Radicados a los que responde</th>
        </tr>
        </thead>
        <tbody>
        @foreach ($pqrs as $pqr)
            <tr>
                <td>{{$pqr->getRadicadoSalida->numero}}</td>
                <td>{{$pqr->getRadicadoSalida->created_at->format('Y-m-d H:i:s')}}</td>
                <td>{{$pqr->hasPeticionario->couldHaveFuncionario->name}}</td>
                <td>{{$pqr->hasClase->name}}</td>
                <td>{{$pqr->asunto}}</td>
                <td>{{$pqr->radicados_respuesta}}</td>
            </tr>
        @endforeach
        </tbody>
    </table>