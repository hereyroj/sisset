<form>
    <input type="hidden" name="idServicio" value="{{$servicio->id}}">
    <h4>Información del servicio</h4>
    <div class="form-group">
        <label for="nombreServicio" class="control-label">Nombre</label>
        <input type="text" id="nombreServicio" name="nombreServicio" class="form-control" value="{{$servicio->name}}">
    </div>
    <div class="form-group">
        <label for="placa_consecutivo" class="control-label">Requiere que la placa se asigne por consecutivo?</label>
        {{Form::select('placa_consecutivo', ['SI'=>'SI','NO'=>'NO'], $servicio->placa_consecutivo, ['class'=>'form-control'])}}
    </div>
    <h4>Vinculación de clases</h4>
    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
            <tr>
                <th>Selección</th>
                <th>Nombre</th>
            </tr>
            </thead>
            <tbody>
            @foreach($clases_vehiculos as $clase)
                <tr>
                    @if($servicio->hasClaseVinculada($clase->id) != null)
                        <td><input type="checkbox" name="clases[]" value="{{$clase->id}}" class="form-control" checked></td>
                        <td>{{$clase->name}}</td>
                    @else
                        <td><input type="checkbox" name="clases[]" value="{{$clase->id}}" class="form-control"></td>
                        <td>{{$clase->name}}</td>
                    @endif
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</form>