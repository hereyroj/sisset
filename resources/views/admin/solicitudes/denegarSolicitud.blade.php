{!! Form::open() !!}
<input type="hidden" name="solicitudId" id="solicitudId" value="{{$id}}">
<div class="form-group">
    <label class="control-label" for="tipoValidacion">Motivo de denegación</label>
    {!! Form::select('tipoDenegacion', $tiposDenegaciones, null, ['class' => 'form-control', 'id'=>'tipoDenegacion']) !!}
</div>
<div class="form-group">
    <label class="control-label" for="observacionDenegacion">Observaciones</label>
    <textarea class="form-control" name="observacionDenegacion" id="observacionDenegacion"></textarea>
</div>
{!! Form::close() !!}