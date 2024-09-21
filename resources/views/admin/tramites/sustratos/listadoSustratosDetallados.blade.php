<table class="table table-striped">
    <thead>
    <tr>
        <th>Estado</th>
        <th>Tipo de sustrato</th>
        <th>Número</th>
        <th>Placa</th>
        <th>Trámites</th>
        <th>Identificación</th>
        <th>Nombres</th>
        <th>Fecha pago</th>
        <th>Liquidación</th>
        <th>Fecha anulación</th>
        <th>Motivo anulación</th>
        <th>Observación anulación</th>
    </tr>
    </thead>
    <tbody>
    @foreach($sustratos as $sustrato)
        <tr>
            <td>
                @if($sustrato->hasAnulacion != null)
                ANULADO
                @else 
                CONSUMIDO 
                @endif
            </td>
            <td>{{$sustrato->hasTipoSustrato->name}}</td>
            <td>{{$sustrato->numero}}</td>
            @if($sustrato->proceso_type == 'App\tramite_servicio_finalizacion')
            <td>{{$sustrato->hasConsumo->hasTramiteServicio->placa}}</td>
            <td>
                @foreach($sustrato->hasConsumo->hasTramiteServicio->hasTramites as $tramite)
                {{$tramite->name}}
                @if (!$loop->last) , @endif
                @endforeach
            </td>    
            <td>{{$sustrato->hasConsumo->hasTramiteServicio->documento_propietario}}</td>
            <td></td>
            <td>{{$sustrato->hasConsumo->hasTramiteServicio->hasRecibos->last()->created_at->format('Y-m-d')}}</td>
            <td>{{$sustrato->hasConsumo->hasTramiteServicio->hasRecibos->last()->numero_sintrat}}</td>   
            @elseif($sustrato->proceso_type == 'App\tramite_licencia') 
            <td></td>
            <td>
                @foreach($sustrato->hasConsumo->hasTramiteSolicitud->hasTramites as $tramite)
                {{$tramite->name}}
                @if (!$loop->last) , @endif
                @endforeach
            </td>
            <td>{{$sustrato->hasConsumo->hasTramiteSolicitud->hasTurnos->last()->hasUsuarioSolicitante->numero_documento}}</td>
            <td>{{$sustrato->hasConsumo->hasTramiteSolicitud->hasTurnos->last()->hasUsuarioSolicitante->nombre_usuario}}</td>
            <td>{{$sustrato->hasConsumo->created_at->format('Y-m-d')}}</td>
            <td>{{$sustrato->hasConsumo->numero_sintrat}}</td>     
            @else 
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>     
            @endif 
            @if($sustrato->hasAnulacion != null)      
            <td>{{$sustrato->hasAnulacion->created_at->format('Y-m-d')}}</td>
            <td>{{$sustrato->hasAnulacion->hasMotivo->name}}</td>
            <td>{{$sustrato->hasAnulacion->observacion}}</td> 
            @else 
            <td></td>   
            <td></td>
            <td></td>
            @endif  
        </tr>
    @endforeach
    </tbody>
</table>