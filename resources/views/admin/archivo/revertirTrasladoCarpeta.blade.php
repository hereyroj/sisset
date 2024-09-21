<form>
    <input type="hidden" name="idCarpetaRevertirTraslado" value="{{$id}}" id="idCarpetaRevertirTraslado">
    <div class="form-group">
        <label for="estadoCarpeta" class="label-control">Revertir a estado</label>
        {!! Form::select('revertirEstado', $estadosCarpetas, null, ['class' => 'form-control', 'id' => 'revertirEstado']) !!}
    </div>
</form>