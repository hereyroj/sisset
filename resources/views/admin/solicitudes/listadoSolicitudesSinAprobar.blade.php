<table class="table table-striped" id="solicitudesSinAprobar">
    <thead>
        <tr>
            <th>Placa</th>
            <th>Hora solicitud</th>
            <th>Entregar a</th>
            <th>Tramites</th>
            <th>Acción</th>
        </tr>
    </thead>
    <tbody>
        @foreach($solicitudes as $solicitud)

        <tr>
            <td>
                {{$solicitud->hasOrigen->placa}}
            </td>
            <td>
                {{$solicitud->created_at}}
            </td>
            <td>
                {{$solicitud->hasOrigen->hasFuncionario->name}}
            </td>
            <td>
                @if($solicitud->origen_type == 'App\tramite_servicio')
                @foreach($solicitud->hasOrigen->hasTramites as $tramite)
                <span class="badge badge-pill badge-primary">{{$tramite->name}}</span>
                @endforeach
                @else
                {{$solicitud->hasOrigen->hasMotivo->name}}
                @endif
            </td>
            <td>
                <div class="btn-group" role="group">
                    <buttom type="button" onclick="aprobarSolicitud({{$solicitud->id}})" class="btn btn-secondary">
                        <span class="glyphicon glyphicon-ok" aria-hidden="true"></span> Autorizar salida
                    </buttom>
                    <button type="button" class="btn btn-secondary" data-toggle="modal" data-target=".modal-denegar" onclick="denegarSolicitud({{$solicitud->id}})">
                        <i class="fas fa-times"></i> Denegar salida
                    </button>
                </div>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>