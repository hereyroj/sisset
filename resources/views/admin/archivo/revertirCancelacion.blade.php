<form>
    <input type="hidden" name="idCarpetaRevertirCancelacion" value="{{$id}}" id="idCarpetaRevertirCancelacion">
    <div class="form-group">
        <label for="estadoCarpeta" class="label-control">Revertir a estado</label>
        {!! Form::select('revertirEstado', $estadosCarpetas, null, ['class' => 'form-control', 'id' => 'revertirEstado']) !!}
    </div>
</form>