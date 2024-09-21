<form>
    <div class="form-group">
        <label for="estadoCarpeta" class="label-control">Nuevo estado</label>
        {!! Form::select('estadoCarpetaId', $estadosCarpetas, null, ['class' => 'form-control', 'id' => 'estadoCarpetaId']) !!}
    </div>
</form>