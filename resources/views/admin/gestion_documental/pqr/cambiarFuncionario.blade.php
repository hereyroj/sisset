<form>
    <input type="hidden" name="id" value="{{$pqrId}}">
    <div class="form-group">
        <label class="control-label" for="funcionario">Funcionario</label>
        {!! Form::select('funcionario', $funcionarios, $funcionarioId, ['class'=>'form-control','id'=>'funcionario']) !!}
    </div>
</form>