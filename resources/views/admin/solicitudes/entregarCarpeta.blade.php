{!! Form::open() !!}
<input type="hidden" name="solicitudId" id="solicitudId" value="{{$id}}">
<div class="form-group">
    <label class="control-label" for="usuarioRecibe">Usuario que la recibe</label>
    {!! Form::select('usuarioRecibe', $usuarios, $usuarioSolicitante, ['class' => 'form-control', 'id'=>'usuarioRecibe']) !!}
</div>
<div class="form-group">
    <label class="control-label" for="PIN">PIN de seguridad</label>
    <input type="password" id="PIN" name="PIN" class="form-control" required>
</div>
{!! Form::close() !!}