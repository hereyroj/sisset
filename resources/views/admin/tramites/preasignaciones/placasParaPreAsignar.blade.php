<h4>Observación del solicitante:</h4>
@if($solicitud->observacion != null)
    {{$solicitud->observacion}}<br>
@else
    Sin observaciones.
@endif
<hr style="margin-bottom: 15px;">
<form>
    <input type="hidden" name="solicitud_preasignacion_id" value="{{$solicitud->id}}">
    @if(isset($placas))
        <table class="table table-striped">
            <thead>
            <tr>
                <th>Selección</th>
                <th>Placa</th>
                <th>Servicio del vehículo</th>
                <th>Clase del vehículo</th>
            </tr>
            </thead>
            <tbody>
            @foreach($placas as $placa)
                <tr>
                    <td>
                        <input type="radio" name="placa_id" value="{{$placa->id}}" class="form-control">
                    </td>
                    <td>
                        {{$placa->name}}
                    </td>
                    <td>
                        {{$placa->hasVehiculoServicio->name}}
                    </td>
                    <td>
                        @foreach($placa->hasVehiculosClases as $clase)
                        {{$clase->name}}
                        @endforeach
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    @else
        <input type="hidden" name="placa_id" value="NO">
        De acuerdo a la configuración del servicio del automotor la asignación de la placa será por consecutivo.
    @endif
</form>