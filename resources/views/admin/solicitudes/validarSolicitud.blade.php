{!! Form::open() !!}
<input type="hidden" name="solicitudId" id="solicitudId" value="{{$id}}">
<div class="form-group">
    <label class="control-label" for="tipoValidacion">Veredicto de la verificación</label>
    {!! Form::select('tipoValidacion', $tiposValidaciones, null, ['class' => 'form-control', 'id'=>'tipoValidacion']) !!}
</div>
<div class="form-group">
    <label class="control-label" for="observacionValidacion">Observaciones</label>
    <textarea class="form-control" name="observacionValidacion" id="observacionValidacion"></textarea>
</div>
{!! Form::close() !!}