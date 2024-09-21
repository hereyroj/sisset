<div class="table-responsive">
    <table class="table table-striped" id="carpetas">
        <thead>
            <tr>
                <th><input type="checkbox" id="chk-multiple"></th>
                <th>Placa</th>
                <th>Clase</th>
                <th>Inventario</th>
                <th>Disponible</th>
                <th>Solicitud activa</th>
                <th>Acción</th>
            </tr>
        </thead>
        <tbody>
            @foreach($carpetas as $carpeta) @if($carpeta->couldHaveCancelacion != null || $carpeta->hasEstado->name == 'CANCELADA')
            <tr style="background-color: #2aabd2;">
                @elseif($carpeta->couldHaveTraslado != null || $carpeta->hasEstado->name == 'TRASLADADA')
                <tr style="background-color: #b6a338;">
                    @else
                    <tr>
                        @endif
                        <td class="multiple"><input type="checkbox" name="multiple[]" value="{{$carpeta->id}}"></td>
                        <td>{{$carpeta->name}}</td>
                        <td>{{$carpeta->hasClase->name}}</td>
                        <td>{{$carpeta->hasEstado->name}}</td>
                        <td>{{$carpeta->available}}</td>
                        @if($carpeta->hasSolicitudPendiente() != null)
                        <td>
                            <button type="button" class="btn btn-secondary" onclick="verSolicitudPendiente({{$carpeta->id}});">
                            <span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span> Ver solicitud
                        </button>
                        </td>
                        @else
                        <td></td>
                        @endif @if($carpeta->couldHaveTraslado != null || $carpeta->hasEstado->name == 'TRASLADADA')
                        <td>
                            <div class="btn-group" role="group" aria-label="...">
                                <button type="button" class="btn btn-secondary" onclick="verTraslado({{$carpeta->id}});">
                                <span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span> Ver traslado
                            </button>
                                <button type="button" class="btn btn-secondary" onclick="historialCarpeta({{$carpeta->id}});">
                                <span class="glyphicon glyphicon-book" aria-hidden="true"></span> Ver historial
                            </button>
                            </div>
                        </td>
                        @elseif($carpeta->couldHaveCancelacion != null || $carpeta->hasEstado->name == 'CANCELADA')
                        <td>
                            <div class="btn-group" role="group" aria-label="...">
                                <button type="button" class="btn btn-secondary" onclick="verCancelacion({{$carpeta->id}});">
                                <span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span> Ver cancelación
                            </button>
                                <button type="button" class="btn btn-secondary" onclick="historialCarpeta({{$carpeta->id}});">
                                <span class="glyphicon glyphicon-book" aria-hidden="true"></span> Ver historial
                            </button>
                            </div>
                        </td>
                        @else
                        <td>
                            <div class="btn-group" role="group" aria-label="...">
                                <button type="button" class="btn btn-secondary" onclick="trasladarCarpeta({{$carpeta->id}});">
                                <span class="glyphicon glyphicon-transfer" aria-hidden="true"></span> Trasladar
                            </button>
                                <button type="button" class="btn btn-secondary" onclick="cancelarCarpeta({{$carpeta->id}});">
                                <span class="glyphicon glyphicon-off" aria-hidden="true"></span> Cancelar matricula
                            </button>
                                <button type="button" class="btn btn-secondary" onclick="historialCarpeta({{$carpeta->id}});">
                                <span class="glyphicon glyphicon-book" aria-hidden="true"></span> Ver historial
                            </button>
                                <button type="button" class="btn btn-secondary" onclick="cambiarEstadoCarpeta({{$carpeta->id, $carpeta->hasEstado->id}} );">
                                <span class="glyphicon glyphicon-random" aria-hidden="true"></span> Cambiar estado
                            </button>
                                <button type="button" class="btn btn-secondary" onclick="editarCarpeta({{$carpeta->id}});">
                                <span class="glyphicon glyphicon-pencil" aria-hidden="true"></span> Editar carpeta
                            </button>
                            </div>
                        </td>
                        @endif
                    </tr>
                    @endforeach
        </tbody>
    </table>
</div>    
<div class="btn-group dropup">
    <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        Para todas las seleccionadas <span class="caret"></span>
    </button>
    <ul class="dropdown-menu">
        <li><a href="#" onclick="multipleCambioEstado();">Cambiar estado</a></li>
        <li><a href="#" onclick="multipleCambioClase();">Cambiar clase</a></li>
        <li><a href="#" onclick="event.preventDefault(); multipleEliminacion();">Eliminar</a></li>
    </ul>
</div>