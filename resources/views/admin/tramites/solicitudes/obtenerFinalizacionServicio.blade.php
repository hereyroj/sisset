
<div id="finalizacionesServicio">
    <div class="cabecera-tabla">
        <div class="btn-group" role="group">
            <button type="button" class="btn btn-primary" onclick="obtenerFinalizacionesServicioModal({{$id}})">
            <i class="fas fa-sync"></i> Actualizar
        </button>
            <button type="button" onclick="agregarFinalizacionServicio({{$id}})" class="btn btn-info">
            <span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Agregar
        </button>
        </div>
    </div>
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
        @if($finalizacion != null)
        <tr>
            <td>
                @if($finalizacion->hasSustrato != null)
                    <button type="button" class="btn btn-danger" onclick="anularSustrato({{$finalizacion->id.','.$finalizacion->hasSustrato->id}})" title="Anular sustrato">{{$finalizacion->hasSustrato->numero}}</button>
                @else
                    NO REQUIERE
                @endif
            </td>
            <td>
                @if($finalizacion->hasSustrato != null)
                    {{$finalizacion->hasSustrato->hasTipoSustrato->name}}
                @else
                    N/A
                @endif
            </td>
            <td>
                @if($finalizacion->hasPlaca != null)
                    {{$finalizacion->hasPlaca->name}}
                @else
                    NO REQUIERE
                @endif
            </td>
            <td>{{$finalizacion->observacion}}</td>
            <td>{{$finalizacion->created_at}}</td>
            <td>{{$finalizacion->hasFuncionario->name}}</td>
        </tr>
        @endif
        </tbody>
    </table>
</div>
<script type="text/javascript" src="{{asset('js/tramites/solicitudes/obtenerFinalizacionServicio.js')}}"></script>
</div>