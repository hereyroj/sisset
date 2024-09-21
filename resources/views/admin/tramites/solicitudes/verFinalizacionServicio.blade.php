<div class="table-responsive">
    <table class="table table-striped">
        <thead>
        <tr>
            <th>Sustrato</th>
            <th>Tipo sustrato</th>
            <th>Placa</th>
            <th>Observación</th>
            <th>Fecha finalización</th>
            <th>Funcionario</th>
        </tr>
        </thead>
        <tbody>
        @if($servicio->hasFinalizacion != null)
            <tr>
                <td>
                    @if($servicio->hasFinalizacion->hasSustrato != null)
                        {{$servicio->hasFinalizacion->hasSustrato->numero}}
                    @else
                        NO REQUIERE
                    @endif
                </td>
                <td>
                    @if($servicio->hasFinalizacion->hasSustrato != null)
                        {{$servicio->hasFinalizacion->hasSustrato->hasTipoSustrato->name}}
                    @else
                        N/A
                    @endif
                </td>
                <td>
                    @if($servicio->hasFinalizacion->hasPlaca != null)
                        {{$servicio->hasFinalizacion->hasPlaca->name}}
                    @else
                        NO REQUIERE
                    @endif
                </td>
                <td>{{$servicio->hasFinalizacion->observacion}}</td>
                <td>{{$servicio->hasFinalizacion->created_at}}</td>
                <td>{{$servicio->hasFinalizacion->hasFuncionario->name}}</td>
            </tr>
        @endif
        </tbody>
    </table>
</div>