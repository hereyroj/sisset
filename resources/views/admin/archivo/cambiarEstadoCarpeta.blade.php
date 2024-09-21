<form>
    <input type="hidden" value="{{$carpeta->id}}" name="carpetaId">
    <div class="form-group">
        <label for="estadoCarpeta" class="label-control">Nuevo estado</label>
        {!! Form::select('estadoCarpetaId', $estadosCarpetas, $carpeta->archivo_carpeta_estado_id, ['class' => 'form-control', 'id' => 'estadoCarpetaId']) !!}
    </div>
</form>